<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GitHubAuthController extends Controller
{
    public function githubCallback(Request $request)
    {
        try {
            $clientId = env('GITHUB_CLIENT_ID');
            $clientSecret = env('GITHUB_CLIENT_SECRET');
            $redirectUri = 'http://127.0.0.1:8000/api/github-callback';

            $code = $request->input('code');

            if (!$code) {
                if (Auth::check()) {
                    $userId = Auth::id();
                    $state = json_encode(['id' => $userId]);
                    $authUri = "https://github.com/login/oauth/authorize?client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";
                    return $authUri;
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                $receivedState = $request->input('state');
                $decodedState = json_decode($receivedState, true);
                $id = $decodedState['id'] ?? null;

                $ch = curl_init("https://github.com/login/oauth/access_token");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'code' => $code,
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'redirect_uri' => $redirectUri,
                ]));
                curl_setopt($ch, CURLOPT_POST, 1);
                $output = curl_exec($ch);
                curl_close($ch);

                parse_str($output, $credentials);

                $user = User::find($id);
                if ($user) {
                    $user->github_token = $credentials['access_token'];
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
