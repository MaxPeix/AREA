<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class spotify_follower_count_change_check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:spotify_follower_count_change_check {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a specific user has a new follower on Spotify';

    public function checkSpotifyToken(User $user)
    {
        $spotifyToken = $user->spotify_token;

        if (!$spotifyToken) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $spotifyToken,
            ])
            ->withOptions([
                'verify' => false
            ])
            ->get('https://api.spotify.com/v1/me');

            if ($response->status() != 200) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            return false;
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
        $validity = $this->checkSpotifyToken($user);
        if (!$validity) {
            Log::info("spotify token is not valid for user: " . $user->id . " - " . $user);
            return 1;
        }
        return 0;
    }
}
