<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DropboxAuthController extends Controller
{
    public function dropboxCallback(Request $request)
    {
        try {
            $clientKey = env('DROPBOX_CLIENT_ID');
            $clientSecret = env('DROPBOX_CLIENT_SECRET');
            $redirectUri = 'http://127.0.0.1:8000/api/dropbox-callback';
            $scope = 'files.metadata.read files.metadata.write files.content.write';

            $code = $request->input('code');

            if (!$code) {
                if (Auth::check()) {
                    $userId = Auth::id();
                    $state = json_encode(['id' => $userId]);
                    $authUri = "https://www.dropbox.com/oauth2/authorize?client_id={$clientKey}&redirect_uri={$redirectUri}&response_type=code&scope={$scope}&state={$state}";
                    return $authUri;
                } else {
                    \Log::info("Unauthorized user");
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                $receivedState = $request->input('state');
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;
                
                $ch = curl_init("https://api.dropboxapi.com/oauth2/token");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'client_id' => $clientKey,
                    'client_secret' => $clientSecret,
                    'redirect_uri' => $redirectUri,
                ]));
                curl_setopt($ch, CURLOPT_POST, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                
                $credentials = json_decode($output, true);
                
                $user = User::find($id);
                if ($user) {
                    $user->dropbox_token = $credentials['access_token'];
                    $user->save();
                } else {
                    \Log::info("No user found with ID: " . $id);
                    \Log::warning("No user found with ID: " . $id);
                }
                return redirect('http://localhost:8080/account');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
