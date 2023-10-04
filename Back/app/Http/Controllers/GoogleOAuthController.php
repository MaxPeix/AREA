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
            $clientId = "1025827948903-tlsoh7n6clf1hgggh752na4gpn0h0n4l.apps.googleusercontent.com";
            $clientSecret = "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";
            $scope = 'email profile https://www.googleapis.com/auth/drive https://mail.google.com/';
            $redirectUri = 'http://127.0.0.1:8000/api/oauth2callback';

            $code = $request->input('code');

            if (!$code) {
                $userId = Auth::id();
                $state = json_encode(['id' => $userId]);
                $authUri = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&state={$state}";
                return $authUri;
            } else {
                $receivedState = $request->input('state');
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;
                $ch = curl_init("https://oauth2.googleapis.com/token");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
                $user = User::find($id);
                if ($user) {
                    $user->google_id = $credentials['access_token'];
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
