<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Action;

class check_for_new_commit extends Command
{
    protected $signature = 'app:check_for_new_commit {user} {action_id}';
    protected $description = 'Check for new commits on a GitHub repository';

    public function handle()
    {
        $user = $this->argument('user');
        $action_id = $this->argument('action_id');
        $action = Action::find($action_id);
        $user = User::find($user);
        $repository = $action->first_parameter;
        if (!$repository) {
            Log::info('Repository not found');
            return 1;
        }
        $token = $user->github_token;
        Log::info("token : " . $token);

        if (!$repository) {
            Log::info('Repository not found');
            return 1;
        }

        $url = "https://api.github.com/repos/$repository/commits";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            'Authorization' => "Bearer $token",
        ])->get($url);

        if ($response->failed()) {
            Log::info('Failed to fetch commits from GitHub.');
            return 1;
        }

        $commits = $response->json();
        $latestCommitSha = $commits[0]['sha'] ?? null;

        if (!$latestCommitSha) {
            Log::info('No commits found.');
            return 1;
        }

        $lastCheckedCommitSha = $action->second_parameter;

        if ($latestCommitSha === $lastCheckedCommitSha) {
            Log::info('No new commits.');
            return 1;
        } else {
            Log::info('New commit detected: ' . $latestCommitSha);
            $action->second_parameter = $latestCommitSha;
            $action->save();
            return 0;
        }
    }
}
