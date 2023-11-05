<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RadioFranceAuthController extends Controller
{
    public function radioFranceCallback(Request $request)
    {
        try {
            $clientId = env('RADIO_FRANCE_CLIENT_ID');
            $clientSecret = env('RADIO_FRANCE_CLIENT_SECRET');
            $redirectUri = 'http://127.0.0.1:8080/api/radio-france-callback';

            $code = $request->input('code');

            if (!$code) {
                $userId = 5; // Remplacez par l'ID de l'utilisateur si nécessaire
                $state = json_encode(['id' => $userId]);
                $authUri = "URL_DE_L'AUTHENTIFICATION_RADIO_FRANCE"; // Remplacez par l'URL d'authentification de Radio France
                return $authUri;
            } else {
                // Échangez le code contre un jeton d'accès Radio France en utilisant cURL
                $ch = curl_init("URL_DE_L'ÉCHANGE_DE_CODE_RADIO_FRANCE"); // Remplacez par l'URL d'échange de code de Radio France
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'code' => $code,
                    'redirect_uri' => $redirectUri,
                    'grant_type' => 'authorization_code',
                ]));
                curl_setopt($ch, CURLOPT_POST, 1);
                $output = curl_exec($ch);
                curl_close($ch);

                $credentials = json_decode($output, true);

                // Enregistrez le jeton d'accès Radio France pour l'utilisateur
                // Vous devrez adapter cela en fonction de la structure de votre modèle User
                $user = User::find($userId);
                if ($user) {
                    $user->radio_france_token = $credentials['access_token'];
                    $user->save();
                } else {
                    \Log::warning("Aucun utilisateur trouvé avec l'ID: " . $userId);
                }

                return redirect('http://localhost:8081/account');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
