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

class drive_rename_last_file_reaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:drive_rename_last_file_reaction {user} {reaction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename the last file modified in Google Drive';

    public function rename_latest_file($user, $newName)
    {
        $listResponse = Http::withToken($user->google_token)
            ->withOptions([
                'verify' => false
            ])
            ->get('https://www.googleapis.com/drive/v3/files', [
                'orderBy' => 'modifiedTime desc',
                'pageSize' => 1,
            ]);

        if ($listResponse->successful()) {
            $files = $listResponse->json()['files'];

            if (count($files) > 0) {
                $latestFile = $files[0];
                $fileId = $latestFile['id'];

                $renameResponse = Http::withToken($user->google_token)
                    ->withOptions([
                        'verify' => false
                    ])
                    ->patch("https://www.googleapis.com/drive/v3/files/{$fileId}", [
                        'json' => [
                            'name' => $newName,
                        ],
                    ]);

                if ($renameResponse->successful()) {
                    return ['message' => 'File renamed successfully'];
                } else {
                    return ['error' => 'Failed to rename file'];
                }
            } else {
                return ['message' => 'No files found'];
            }
        } else {
            return ['error' => 'Failed to list files'];
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
        Log::info($this->rename_latest_file($user, $content));
    }
}
