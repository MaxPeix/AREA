<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class GoogleOAuthController extends Controller
{
    public function oauth2callback(Request $request)
    {
        $clientId = "1025827948903-tlsoh7n6clf1hgggh752na4gpn0h0n4l.apps.googleusercontent.com";
        $clientSecret = "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";
        $scope = 'email profile';
        $redirectUri = 'http://127.0.0.1:80/api';

        $code = $request->input('code');

        if (!$code) {
            $authUri = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}";
            return redirect($authUri);
        } else {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code'
            ]);

            $credentials = json_decode($response->body(), true);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $credentials['access_token'],
            ])->get('https://openidconnect.googleapis.com/v1/userinfo');

            $userInfo = json_decode($response->body(), true);

            $jwtPayload = [
                'exp' => Carbon::now()->addHours(1)->timestamp,
                'iat' => Carbon::now()->timestamp,
                'sub' => $credentials['access_token'],
                'data' => $userInfo,
            ];

            $jwtSecret = env('JWT_SECRET');
            $jwtToken = JWT::encode($jwtPayload, $jwtSecret, 'HS256');

            return json_encode([
                'token' => $jwtToken,
                'user' => $userInfo,
            ]);
        }
    }
}
