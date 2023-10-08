<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleOAuthController extends Controller
{
    public function oauth2callback(Request $request)
    {
        try {
            $clientIdGoogle = env('GOOGLE_CLIENT_ID');
            $clientIdSecretGoogle = env('GOOGLE_CLIENT_SECRET');
            $scope = 'email profile https://www.googleapis.com/auth/drive https://mail.google.com/';
            $redirectUri = 'http://127.0.0.1:8000/api/oauth2callback';

            $code = $request->input('code');

            if (!$code) {
                if (Auth::check()) {
                    $userId = Auth::id();
                    $state = json_encode(['id' => $userId]);
                    $authUri = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$clientIdGoogle}&redirect_uri={$redirectUri}&scope={$scope}&state={$state}";
                    return $authUri;
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                $receivedState = $request->input('state');
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;
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

                $credentials = json_decode($output, true);
                $user = User::find($id);
                if ($user) {
                    $user->google_token = $credentials['access_token'];
                    $user->save();
                    $accessToken = $credentials['access_token'];
                    $ch = curl_init("https://www.googleapis.com/oauth2/v1/userinfo?access_token={$accessToken}");
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $userInfo = curl_exec($ch);
                    curl_close($ch);
                    
                    $userInfo = json_decode($userInfo, true);
                    
                    if (isset($userInfo['email'])) {
                        $email = $userInfo['email'];
                        $user->gmail_adress = $email;
                        $user->save();
                    } else {
                        \Log::warning("Impossible de récupérer l'adresse e-mail");
                    }
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
