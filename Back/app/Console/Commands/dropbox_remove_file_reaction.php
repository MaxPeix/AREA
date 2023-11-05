<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class dropbox_remove_file_reaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dropbox_remove_file_reaction {user} {reaction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a file in Dropbox';

    public function handle()
    {
        $user = $this->argument('user');
        $reactionId = $this->argument('reaction');
        $user = User::find($user);
        $reaction = Reaction::find($reactionId);

        if (!$user) {
            Log::info('User not found');
            return 1;
        }

        if (!$reaction) {
            Log::info('Reaction not found');
            return 1;
        }

        // Extract the file path from the reaction parameter
        $filePath = $reaction->first_parameter; // Assuming the parameter contains the file path

        $accessToken = $user->dropbox_token; // Replace with your Dropbox access token
        $dropboxApiUrl = 'https://api.dropboxapi.com/2/files/delete_v2'; // Dropbox API endpoint for file deletion

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ];

        $data = [
            'path' => $filePath, // Specify the path of the file to delete
        ];

        $response = Http::withHeaders($headers)->withOptions(['verify' => false])->post($dropboxApiUrl, $data);

        Log::info('Dropbox API Response: ' . $response->body());

        if ($response->failed()) {
            Log::info('Failed to delete the file in Dropbox.');
            return 1;
        }

        Log::info('File deleted in Dropbox: ' . $filePath);
        return 0;
    }
}
