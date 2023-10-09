<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class mail_received_checks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mail_received_checks {user}';

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $user = User::find($userId);
        if (!$user) {
            Log::error('User not found');
            return 1;
        }
        $validity = $this->checkGoogleToken($user);
        if (!$validity) {
            Log::info("google token not is valid for user: " . $user->id . " - " . $user);
            return 1;
        }
        $googleToken = $user->google_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/gmail/v1/users/me/messages?maxResults=10");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $googleToken
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::info("cURL error: " . curl_error($ch));
            curl_close($ch);
            return 1;
        }
        curl_close($ch);
        $body = json_decode($response, true);
        if (isset($body['messages']) && count($body['messages']) > 0) {
            $newestMailId = $body['messages'][0]['id'];
            if ($newestMailId !== $user->gmail_last_mail_id) {
                $user->gmail_last_mail_id = $newestMailId;
                $user->save();
                return 0;
            }
        }
        return 1;
    }
}
