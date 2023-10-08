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

    public function encode($v) {
        return base64_encode($v);
    }

    public function create_message($fromEmail, $toEmail) {
        $subject = "=?utf-8?B?" . $this->encode("[AREA] reaction") . "?=";
        $date = date('r');
        $message = "To: $toEmail\r\n";
        $message .= "From: $fromEmail\r\n";
        $message .= "Subject: $subject\r\n";
        $message .= "Date: $date\r\n";
        $message .= "Content-Type: multipart/alternative; boundary=boundaryboundary\r\n\r\n";
        $message .= "--boundaryboundary\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= $this->encode("La réaction a correctement été executé à la réception d'un mail.") . "\r\n\r\n";
        $message .= "--boundaryboundary";

        return $message;
    }

    public function execute_reaction($reactions, $user)
    {
        Log::info("execute reaction");
        Log::info("user: " . $user);
        $reaction = $reactions[0];
        $service = $reaction->service;
        Log::info('service: ' . $service);
        Log::info("Recipient Email: " . $user->gmail_adress);
    
        if ($service->id == 14) {
            Log::info("envoies d'un mail");
            Log::info($service->service_name);
    
            $recipientEmail = $user->gmail_adress;
            Log::info("Recipient Email: " . $recipientEmail);
            $rawMessage = $this->create_message($recipientEmail, $recipientEmail);

            $base64RawMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');

            $postData = json_encode(['raw' => $base64RawMessage]);
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/gmail/v1/users/me/messages/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $user->google_token,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                Log::info("cURL error: " . curl_error($ch));
                curl_close($ch);
                return response()->json(['message' => 'An error occurred while sending the mail'], 500);
            }
    
            curl_close($ch);
            $body = json_decode($response, true);
            Log::info($body);
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
                                        continue;
                                    }

                                    curl_close($ch);
                                    $body = json_decode($response, true);

                                    if (isset($body['messages']) && count($body['messages']) > 0) {
                                        $newestMailId = $body['messages'][0]['id'];
                                        if ($newestMailId !== $user->gmail_last_mail_id) {
                                            $user->gmail_last_mail_id = $newestMailId;
                                            $user->save();
                                            $this->execute_reaction($action->reactions, $user);
                                        }
                                    }
                                } else {
                                    Log::info("google token not is valid for user: " . $user->id . " - " . $user);
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
