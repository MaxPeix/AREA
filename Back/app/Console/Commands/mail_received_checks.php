<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\User;

class mail_received_checks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mail_received_checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a mail is received from user where google is validated and have an area validated with an action with name receive a mail and a reaction';

    public function checkGoogleToken(User $user)
    {
        $googleToken = $user->google_token;

        if (!$googleToken) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v3/tokeninfo?access_token={$googleToken}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return response()->json(['message' => 'An error occurred while checking the Google token'], 500);
        }
        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (isset($decodedResponse['error_description'])) {
            return false;
        } else {
            return true;
        }
    }

    public function execute_reaction($reactions, $user)
    {
        try {
            for ($i = 0; $i < count($reactions); $i++) {
                \Log::info("reaction: " . $reactions[$i]);
            }
        } catch (\Throwable $th) {
            \Log::info($th);
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $areas = Area::with([
            'user',
            'actions.service',
            'actions.reactions.service',
        ])->get();

        foreach ($areas as $area) {
            $user = User::find($area->users_id);
            // $this->action_is_valid($area, $user);

            if ($area->activated) {
                foreach ($area->actions as $action) {
                    if ($action->activated) {
                        if ($action->service) {
                            if ($action->service->id == 2) {
                                $validity = $this->checkGoogleToken($user);
                                if ($validity) {
                                    $googleToken = $user->google_token;
                                    \Log::info($googleToken);
                                    \Log::info("google token is valid for user: " . $user->id . " - " . $user->name);

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/gmail/v1/users/me/messages");
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Authorization: Bearer ' . $googleToken
                                    ]);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    $response = curl_exec($ch);
    
                                    if (curl_errno($ch)) {
                                        \Log::info("cURL error: " . curl_error($ch));
                                        curl_close($ch);
                                        continue;
                                    }

                                    curl_close($ch);
                                    $body = json_decode($response, true);

                                    if (isset($body['messages']) && count($body['messages']) > 0) {
                                        $newestMailId = $body['messages'][2]['id'];
                                        \Log::info("last mail id: " . $user->gmail_last_mail_id);
                                        \Log::info("new mail id: " . $newestMailId);
    
                                        if ($newestMailId !== $user->gmail_last_mail_id) {
                                            \Log::info("New mail received.");
                                            $user->gmail_last_mail_id = $newestMailId;
                                            $user->save();
                                            $this->execute_reaction($action->reactions, $user);
                                        }
                                    }
                                } else {
                                    \Log::info("google token not is valid for user: " . $user->id . " - " . $user);
                                }
                            }
                        }
                    }
                }
            }
        }
        return 0;
    }
}
