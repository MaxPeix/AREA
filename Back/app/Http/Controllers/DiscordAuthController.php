<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscordAuthController extends Controller
{
    public function discordCallback(Request $request)
    {
        try {
            $clientId = env('DISCORD_CLIENT_ID');
            $clientSecret = env('DISCORD_CLIENT_SECRET');
            $redirectUri = 'http://127.0.0.1:8000/api/discord-callback';
            $scope = 'identify email connections guilds.members.read';

            $code = $request->input('code');

            if (!$code) {
                if (Auth::check()) {
                    $userId = Auth::id(); // Remplacez par l'ID de l'utilisateur si nécessaire
                    $state = json_encode(['id' => $userId]);
                    $authUri = "https://discord.com/api/oauth2/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}&scope=dm_channels.read%20identify%20email%20messages.read%20connections";
                    \Log::info($authUri);
                    return $authUri;
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                // Échangez le code contre un jeton d'accès Discord en utilisant cURL
                $receivedState = $request->input('state');
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;

                $ch = curl_init("https://discord.com/api/oauth2/token");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUri
                ]));
                curl_setopt($ch, CURLOPT_POST, 1);
                $output = curl_exec($ch);
                curl_close($ch);

                $credentials = json_decode($output, true);
                $user = User::find($id);
                if ($user) {
                    $user->discord_token = $credentials['access_token'];
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
