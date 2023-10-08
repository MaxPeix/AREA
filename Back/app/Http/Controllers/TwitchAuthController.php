<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitchAuthController extends Controller
{
    public function twitchCallback(Request $request)
    {
        try {
            $clientId = env('TWITCH_CLIENT_ID');
            $clientSecret = env('TWITCH_CLIENT_SECRET');
            $redirectUri = 'https://127.0.0.1:8000/api/twitch-callback';
            
            $code = $request->input('code');
            
            if (!$code) {
                $userId = 5; // Remplacez par l'ID de l'utilisateur si nécessaire
                $state = json_encode(['id' => $userId]);
                $authUri = "https://id.twitch.tv/oauth2/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}&scope=user_read";
                return $authUri;
            } else {
                // Échangez le code contre un jeton d'accès Twitch en utilisant cURL
                $ch = curl_init("https://id.twitch.tv/oauth2/token");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUri,
                ]));
                curl_setopt($ch, CURLOPT_POST, 1);
                $output = curl_exec($ch);
                curl_close($ch);

                $credentials = json_decode($output, true);

                // Enregistrez le jeton d'accès Twitch pour l'utilisateur
                // Vous devrez adapter cela en fonction de la structure de votre modèle User
                $user = User::find($userId);
                if ($user) {
                    $user->twitch_token = $credentials['access_token'];
                    $user->save();
                } else {
                    \Log::warning("Aucun utilisateur trouvé avec l'ID: " . $userId);
                }

                return redirect('http://localhost:8080/account');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
