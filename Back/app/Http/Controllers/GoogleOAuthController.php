<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class GoogleOAuthController extends Controller
{
    public function oauth2callback(Request $request)
    {
        $clientId = "1025827948903-tlsoh7n6clf1hgggh752na4gpn0h0n4l.apps.googleusercontent.com";
        $clientSecret = "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";
        $scope = 'email profile';
        $redirectUri = 'http://127.0.0.1:8000/api/oauth2callback';

        $code = $request->input('code');

        if (!$code) {
            $authUri = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}";
            return json_encode([
                'authUri' => $authUri,
            ]);
        } else {
            $ch = curl_init("https://oauth2.googleapis.com/token");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code'
            ]));
            curl_setopt($ch, CURLOPT_POST, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            
            $credentials = json_decode($output, true);

            $ch = curl_init("https://openidconnect.googleapis.com/v1/userinfo");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $credentials['access_token']
            ]);
            $output = curl_exec($ch);
            curl_close($ch);
            
            $userInfo = json_decode($output, true);

            return json_encode([
                'user' => $userInfo,
            ]);
        }
    }
}
