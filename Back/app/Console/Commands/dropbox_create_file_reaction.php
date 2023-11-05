<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class dropbox_create_file_reaction extends Command
{
        /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dropbox_create_file_reaction {user} {reaction}';

    /**
     * The console command description.
     *
     * @var string
     */    
    protected $description = 'Create a file in Dropbox';

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
    
        // Extract reaction parameters
        $title = $reaction->first_parameter; // Assuming title is the first parameter
        $content = $reaction->second_parameter; // Assuming content is the second parameter
    
        \Log::info("title: " . $title);
        \Log::info("content: " . $content);
    
        $accessToken = $user->dropbox_token; // Replace with your Dropbox access token
        $dropboxFolder = '/'; // Set the Dropbox folder path where you want to create the file
    
        $dropboxApiUrl = 'https://content.dropboxapi.com/2/files/upload';
    
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/octet-stream',
            'Dropbox-API-Arg' => json_encode([
                'path' => $dropboxFolder . $title,
                'mode' => 'add',
                'autorename' => true,
            ]),
        ];
    
        $response = Http::withHeaders($headers)->post($dropboxApiUrl, $content);
    
        Log::info('Dropbox API Response: ' . $response->body());
    
        if ($response->failed()) {
            Log::info('Failed to create a file in Dropbox.');
            return 1;
        }
    
        Log::info('File created in Dropbox: ' . $title);
        return 0;
    }
    
    
}