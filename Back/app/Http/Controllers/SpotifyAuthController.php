<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SpotifyAuthController extends Controller
{
    public function spotifyCallback(Request $request)
    {
        try {
            $clientId = '9095a14ddbe547f6a6627166a3d86559';
            $clientSecret = '5ce501e07077419fa59f3a9e4a2d577a';
            $scope = 'user-read-private user-read-email';
            $redirectUri = 'http://127.0.0.1:8000/api/spotify-callback';
    
            $code = $request->input('code');
    
            if (!$code) {
                $userId = 5;
                $state = json_encode(['id' => $userId]);
                $authUri = "https://accounts.spotify.com/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&state={$state}";
                return $authUri;
            } else {
                $receivedState = $request->input('state');
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;
    
                // Échangez le code contre un jeton d'accès Spotify en utilisant cURL
                $ch = curl_init("https://accounts.spotify.com/api/token");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUri,
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ]));
                curl_setopt($ch, CURLOPT_POST, 1);
                $output = curl_exec($ch);
                curl_close($ch);
    
                $credentials = json_decode($output, true);
                
                // Enregistrez le jeton d'accès Spotify pour l'utilisateur
                // Vous devrez adapter cela en fonction de la structure de votre modèle User
                $user = User::find($id);
                if ($user) {
                    $user->spotify_token = $credentials['access_token'];
                    $user->save();
                } else {
                    \Log::warning("Aucun utilisateur trouvé avec l'ID: " . $id);
                }
                
                return redirect('http://localhost:8080/account');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
    
}
