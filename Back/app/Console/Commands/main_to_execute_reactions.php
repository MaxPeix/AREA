<?php

namespace App\Console\Commands;

use App\Models\Action;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class main_to_execute_reactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:main_to_execute_reactions {action} {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $actionId = $this->argument('action');
        $userId = $this->argument('user');
        Log::info("Action id: " . $actionId);

        $action = Action::with('reactions')->find($actionId);

        if ($action) {
            Log::info('Action found: ', ['action' => $action->toArray()]);

            $reactions = $action->reactions;

            foreach ($reactions as $reaction) {
                $service_id = $reaction->services_id;
                
                // send a mail
                if ($service_id == 14) {
                    Log::info("sending a mail");
                    Artisan::call('app:send_a_mail', [
                        'user' => $userId
                    ]);
                }
            }
        } else {
            Log::error("Action with id {$actionId} not found.");
        }

        return 0;
    }
}