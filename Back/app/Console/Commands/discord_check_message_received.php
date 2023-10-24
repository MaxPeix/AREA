<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class discord_check_message_received extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:discord_check_message_received {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a message is received on discord';

    public function checkDiscordToken(User $user)
    {
        $discordToken = $user->discord_token;

        if (!$discordToken) {
            Log::info('No discord token');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $discordToken,
            ])
            ->withOptions([
                'verify' => false
            ])
            ->get('https://discord.com/api/users/@me');

            if ($response->status() != 200) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all Discord messages for the user.
     */
    private function fetchDiscordMessages(User $user)
    {
        $discordToken = $user->discord_token;

        if (!$discordToken) {
            Log::info('No discord token');
            return null;
        }

        try {
            $channelsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $discordToken,
            ])
            ->get('https://discord.com/api/users/@me/guilds');

            if ($channelsResponse->status() != 200) {
                Log::error('Failed to fetch Discord channels: ' . $channelsResponse->status());
                return null;
            }

            $channels = $channelsResponse->json();

            $discordMessages = [];

            foreach ($channels as $channel) {
                $channelId = $channel['id'];
                $messagesResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $discordToken,
                ])->get("https://discord.com/api/channels/1166358218088394772/messages"); // Utilisez le $channelId correspondant
            
                if ($messagesResponse->status() == 200) {
                    $messages = $messagesResponse->json();
                    foreach ($messages as $message) {
                        Log::info("Received message in channel $channelId: " . $message['content']);
                    }
                } else {
                    Log::error('Failed to fetch messages for channel ' . $channelId . ': ' . $messagesResponse->status());
                }
            }

            Log::info('Fetched ' . count($discordMessages) . ' Discord messages.');
            return $discordMessages;
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return null;
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
    
        $response = $this->checkDiscordToken($user);
    
        if (!$response) {
            Log::info("Discord token is not valid for user: " . $user->id . " - " . $user);
            return 1;
        }
    
        // Incluez le code pour vérifier et afficher chaque message Discord reçu ici.
        
        // Exemple : 
        $discordMessages = $this->fetchDiscordMessages($user);
    
        if ($discordMessages) {
            foreach ($discordMessages as $message) {
                Log::info("Received message: " . $message['content']);
                return 0;
            }
        } else {
            Log::info("No message received");
            return 1;
        }
    
        return 0;
    }

}