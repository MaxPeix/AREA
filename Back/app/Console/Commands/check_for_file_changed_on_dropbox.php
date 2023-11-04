<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Action;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class check_for_file_changed_on_dropbox extends Command
{
    protected $signature = 'app:check_for_file_changed_on_dropbox {user} {action_id}';
    protected $description = 'Check for file changes in Dropbox';

    public function handle()
    {
        $userId = $this->argument('user');
        $actionId = $this->argument('action_id');

        // Récupérer l'utilisateur et l'action associée
        $user = User::find($userId);
        $action = Action::find($actionId);

        if (!$user || !$action) {
            Log::error('User or action not found');
            return 1;
        }

        // Remplacez 'YOUR_ACCESS_TOKEN' par votre token d'accès Dropbox
        $accessToken = $user->dropbox_token;

        // URL de l'API Dropbox pour lister les fichiers dans un dossier
        $dropboxApiUrl = 'https://api.dropboxapi.com/2/files/list_folder';

        // Le dossier à surveiller dans Dropbox (dans cet exemple, "Montre")
        $folderPath = $action->first_parameter;

        // Configuration de la requête à l'API Dropbox
        $response = Http::withToken($accessToken)
            ->post($dropboxApiUrl, [
                'path' => $folderPath,
                'recursive' => false,
            ]);

        if ($response->successful()) {
            $data = $response->json();

            if (count($data['entries']) > 0) {
                Log::info('Folder is not empty.');
            
                foreach ($data['entries'] as $file) {
                    Log::info('File changed or added: ' . $file['name']);
                }
                return 0;
            } else {
                Log::info('Folder is empty.');
                return 1;
            }

        } else {
            Log::error('Failed to fetch Dropbox folder contents: ' . $response->status());
            return 1;
        }
    }
}
