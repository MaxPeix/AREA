<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Reaction;

class create_issue extends Command
{
    protected $signature = 'app:create_issue {user} {reaction}';
    protected $description = 'Create a GitHub issue as a reaction';

    public function handle()
    {
        $user = $this->argument('user');
        $reactionId = $this->argument('reaction');
        $user = User::find($user);
        $reaction = Reaction::find($reactionId);

        if (!$user) {
            Log::info('User not found');
            return 1;
        }

        if (!$reaction) {
            Log::info('Reaction not found');
            return 1;
        }

        // Extract reaction parameters
        $repository = $reaction->first_parameter;
        $title = "AREA automatic issue";
        $body = $reaction->second_parameter;

        $token = $user->github_token;
        Log::info("Token: " . $token);

        $url = "https://api.github.com/repos/$repository/issues";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            'Authorization' => "Bearer $token",
        ])->post($url, [
            'title' => $title,
            'body' => $body,
        ]);

        if ($response->failed()) {
            Log::info('Failed to create a new issue on GitHub.');
            return 1;
        }

        $issue = $response->json();
        $issueId = $issue['id'] ?? null;

        if (!$issueId) {
            Log::info('Failed to create a new issue.');
            return 1;
        } else {
            Log::info('New issue created: ' . $issueId);
            return 0;
        }
    }
}
