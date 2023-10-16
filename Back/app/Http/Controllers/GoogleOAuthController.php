<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class GoogleOAuthController extends Controller
{

    private function requestGoogleToken($code, $clientIdGoogle, $clientIdSecretGoogle, $redirectUri)
    {
        $ch = curl_init("https://oauth2.googleapis.com/token");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code' => $code,
            'client_id' => $clientIdGoogle,
            'client_secret' => $clientIdSecretGoogle,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ]));
        curl_setopt($ch, CURLOPT_POST, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }

    private function getUserGoogleInfo($accessToken)
    {
        $ch = curl_init("https://www.googleapis.com/oauth2/v1/userinfo?access_token={$accessToken}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $userInfo = curl_exec($ch);
        curl_close($ch);

        return json_decode($userInfo, true);
    }

    public function oauth2callback(Request $request)
    {
        try {
            $clientIdGoogle = env('GOOGLE_CLIENT_ID');
            $clientIdSecretGoogle = env('GOOGLE_CLIENT_SECRET');
            $scopeCron = 'email profile https://www.googleapis.com/auth/drive https://mail.google.com/';
            $scopeLogin = 'email profile';
            $redirectUri = 'http://127.0.0.1:8000/api/oauth2callback';

            $code = $request->input('code');

            $receivedState = $request->input('state');
            if ($receivedState) {
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;
            } else {
                $decodedState = null;
            }

            if (!$code) {
                if (Auth::check()) {
                    $userId = Auth::id();
                    $state = json_encode(['id' => $userId]);
                    $authUri = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$clientIdGoogle}&redirect_uri={$redirectUri}&scope={$scopeCron}&state={$state}";
                    return $authUri;
                } else {
                    $state = json_encode(['connected' => false]);
                    $authUri = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$clientIdGoogle}&redirect_uri={$redirectUri}&scope={$scopeLogin}&state={$state}";
                    return $authUri;
                }
            } else if ($decodedState != null && $id == null) {
                $credentials = $this->requestGoogleToken($code, $clientIdGoogle, $clientIdSecretGoogle, $redirectUri);
                if ($credentials == null)
                    return "error";
                $accessToken = $credentials['access_token'];
                $userInfo = $this->getUserGoogleInfo($accessToken);
                if (!$userInfo)
                    return "error userinfo is null";
                if (!isset($userInfo['email'])) {
                    return 'Error decoding userInfo[email]';
                }
                $email = $userInfo['email'];
                $username = $userInfo['given_name'] ?? $user_info['name'] ?? $email ?? null;
                if ($username == null)
                    return 'Error decoding userInfo[given_name] or userInfo[name] or userInfo[email]';
                $user = User::where('email', $email)->first();
                if ($user) {
                    if ($user instanceof \PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject) {
                        $jwtToken = Auth::setTTL(90 * 24 * 60)->claims(['username' => $username, 'email' => $email])->fromUser($user);
                        return redirect("http://localhost:8080/home?jwt={$jwtToken}");
                    } else {
                        return 'Error decoding user please contact site admin';
                    }
                } else {
                    $user = User::create([
                        'email' => $email,
                        'roles' => 'user',
                        'username' => $username,
                        'password' => 'connected with google',
                    ]);

                    if (!$user) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'User creation failed',
                        ], 500);
                    }
                    $minutes = 90 * 24 * 60;
                    $jwtToken = Auth::setTTL($minutes)->claims(['username' => $user->username, 'email' => $user->email])->fromUser($user);
                    return redirect("http://localhost:8080/home?jwt={$jwtToken}?fromregister=true");
                }
            } else {
                $id = $decodedState['id'] ?? null;
                $credentials = $this->requestGoogleToken($code, $clientIdGoogle, $clientIdSecretGoogle, $redirectUri);
                $user = User::find($id);
                if ($user) {
                    $user->google_token = $credentials['access_token'];
                    $user->save();
                    $accessToken = $credentials['access_token'];
                    $userInfo = $this->getUserGoogleInfo($accessToken);
                    
                    if (isset($userInfo['email'])) {
                        $email = $userInfo['email'];
                        $user->gmail_adress = $email;
                        $user->save();
                    } else {
                        Log::warning("Impossible de récupérer l'adresse e-mail");
                    }
                } else {
                    Log::warning("Aucun utilisateur trouvé avec l'ID: " . $id);
                }
                return redirect('http://localhost:8080/account');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
