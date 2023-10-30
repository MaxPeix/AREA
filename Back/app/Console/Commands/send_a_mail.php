<?php

namespace App\Console\Commands;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class send_a_mail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send_a_mail {user} {reaction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function encode($v) {
        return base64_encode($v);
    }

    public function create_message($fromEmail, $toEmail, $content)
    {
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
        $message .= $this->encode($content) . "\r\n\r\n";
        $message .= "--boundaryboundary";

        return $message;
    }

    public function execute_reaction($user, $reaction_id)
    {
        $reaction = Reaction::find($reaction_id);

        $recipientEmail = $reaction->first_parameter;
        $content = $reaction->second_parameter;
        $rawMessage = $this->create_message($user->gmail_adress, $recipientEmail, $content);

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
        Log::info("envoyé depuis " . $user->gmail_adress . " vers " . $recipientEmail . " avec le contenu " . $content);
        Log::info($body);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $user = User::find($userId);
        $reaction_id = $this->argument('reaction');
        if (!$reaction_id) {
            Log::error('Action not found');
            return 1;
        }
        if (!$user) {
            Log::error('User not found');
            return 1;
        }
        $this->execute_reaction($user, $reaction_id);
        return 0;
    }
}
