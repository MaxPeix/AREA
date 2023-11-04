<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckTokenService extends Controller
{
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

    public function checkDiscordToken(User $user)
    {
        $discordToken = $user->discord_token;

        if (!$discordToken) {
            Log::info('No discord token');
            return false;
        }
        \Log::info("discord token: " . $discordToken);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $discordToken,
            ])
            ->withOptions([
                'verify' => false
            ])
            ->get('https://discord.com/api/users/@me');
            Log::info($response);

            if ($response->status() != 200) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    public function checkGithubToken(User $user)
    {
        $githubToken = $user->github_token;

        if (!$githubToken) {
            Log::info('No github token');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $githubToken,
            ])
            ->withOptions([
                'verify' => false, // You may want to handle SSL verification properly in production.
            ])
            ->get('https://api.github.com/user');

            if ($response->status() != 200) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    public function checkDropboxToken(User $user)
    {
        $dropboxToken = $user->dropbox_token;
    
        if (!$dropboxToken) {
            return false;
        }
    
        // try {
        //     $response = Http::withHeaders([
        //         'Authorization' => 'Bearer ' . $dropboxToken,
        //     ])
        //     ->get('https://api.dropboxapi.com/2/users/get_current_account');
    
        //     if ($response->status() == 200) {
        //         return true;
        //     }
    
        //     return false;

        return true;
    
        // } catch (\Exception $e) {
        //     return false;
        // }
    }
    
    public function checkTokensValidity()
    {
        $user = Auth::user();
        $google = $this->checkGoogleToken($user);
        $spotify = $this->checkSpotifyToken($user);
        $discord = $this->checkDiscordToken($user);
        $github = $this->checkGithubToken($user);
        $dropbox = $this->checkDropboxToken($user);
        
        return [
            'google' => $google,
            'spotify' => $spotify,
            'discord' => $discord,
            'github' => $github,
            'dropbox' => $dropbox,
        ];
    }
}
