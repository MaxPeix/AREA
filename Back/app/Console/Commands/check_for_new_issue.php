<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Action;

class check_for_new_issue extends Command
{
    protected $signature = 'app:check_for_new_issue {user} {action_id}';
    protected $description = 'Check for new issues on a GitHub repository';

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

        $url = "https://api.github.com/repos/$repository/issues";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            'Authorization' => "Bearer $token",
        ])->get($url);

        if ($response->failed()) {
            Log::info('Failed to fetch issues from GitHub.');
            return 1;
        }

        $issues = $response->json();
        $latestIssueId = $issues[0]['id'] ?? null;

        if (!$latestIssueId) {
            Log::info('No issues found.');
            return 1;
        }

        $lastCheckedIssueId = $action->second_parameter;

        if ($latestIssueId == $lastCheckedIssueId) {
            Log::info('No new issues.');
            return 1;
        } else {
            Log::info('New issue detected: ' . $latestIssueId);
            $action->second_parameter = $latestIssueId;
            $action->save();
            return 0;
        }
    }
}
