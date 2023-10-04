<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyAuthController extends Controller
{
    public function redirectToSpotify()
    {
        $clientId = '9095a14ddbe547f6a6627166a3d86559';
        $redirectUri = 'http://localhost:8000/api/spotify-callback';
        $scope = 'user-read-email';
        $state = bin2hex(random_bytes(8));
    
        $url = "https://accounts.spotify.com/authorize?response_type=code&client_id=$clientId&scope=$scope&redirect_uri=$redirectUri&state=$state";
    
        // Utilisation de cURL pour rediriger
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
    

    public function handleCallback(Request $request)
    {
        // Récupérez le code de la requête
        $code = $request->input('code');
    
        $clientId = '9095a14ddbe547f6a6627166a3d86559';
        $clientSecret = '5ce501e07077419fa59f3a9e4a2d577a';
        $redirectUri = 'http://localhost:8000/api/spotify-callback';
    
        // Utilisation de cURL pour envoyer la demande de token
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Traitement de la réponse JSON
        $data = json_decode($response, true);
        \Log::info($response);
    
        $accessToken = $data['access_token'];
    
        // Utilisez $accessToken pour accéder à l'API Spotify et obtenir les informations de l'utilisateur.
    }
    
}
