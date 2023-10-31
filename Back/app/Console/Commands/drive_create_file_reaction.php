<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Area;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class drive_create_file_reaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:drive_create_file_reaction {user} {reaction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if hour is reached';

    public function create_a_file($user, $name, $content)
    {
        // Création du fichier métadonnées
        $response = Http::withToken($user->google_token)
            ->withOptions([
                'verify' => false
            ])
            ->post('https://www.googleapis.com/drive/v3/files', [
                'json' => [
                    'name' => $name,
                    'mimeType' => 'text/plain',
                ],
            ]);

        if ($response->successful()) {
            $file = $response->json();
            $fileId = $file['id'];

            // Ajout du contenu au fichier
            $updateResponse = Http::withToken($user->google_token)
                ->withOptions([
                    'verify' => false
                ])
                ->patch("https://www.googleapis.com/upload/drive/v3/files/{$fileId}?uploadType=media", $content, [
                    'headers' => [
                        'Content-Type' => 'text/plain',
                        'name' => $name,
                    ],
                ]);

            if ($updateResponse->successful()) {
                return $updateResponse->json();
            } else {
                return ['error' => 'Failed to upload content'];
            }
        } else {
            return ['error' => 'Failed to create file'];
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $user = User::find($userId);
        $reactionId = $this->argument('reaction');
        $reaction = Reaction::find($reactionId);

        if (!$user) {
            Log::error('User not found');
            return 1;
        }

        if (!$reaction) {
            Log::error('Reaction not found');
            return 1;
        }

        if (!$user->google_token) {
            Log::error('User has no google token');
            return 1;
        }
        $content = $reaction->first_parameter;
        if (!$content) {
            Log::error('Reaction has no content');
            return 1;
        }
        Log::info($this->create_a_file($user, "[AREA] [REACTION] test file name", $content));
    }
}