<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\AreaHistorique;
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
    protected $signature = 'app:main_to_execute_reactions {action} {user} {area_name}';

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
        $area_name = $this->argument('area_name');
        $description = "La reaction";
        date_default_timezone_set('Europe/Paris');

        $action = Action::with('reactions')->find($actionId);

        if ($action) {
            Log::info('Action found: ', ['action' => $action->toArray()]);

            $reactions = $action->reactions;

            foreach ($reactions as $reaction) {
                $service_id = $reaction->services_id;
                $trigerred = false;
                
                // send a mail
                if ($service_id == 14) {
                    Log::info("sending a mail");
                    Artisan::call('app:send_a_mail', [
                        'user' => $userId,
                        'reaction' => $reaction->id
                    ]);
                    $description = $description . " send a mail a été déclenchée à " . date("H:i:s") . ".";
                    $trigerred = true;
                }

                // create a file
                if ($service_id == 21) {
                    Log::info("create a file");
                    Artisan::call('app:drive_create_file_reaction', [
                        'user' => $userId,
                        'reaction' => $reaction->id
                    ]);
                    $description = $description . " create a file a été déclenchée à " . date("H:i:s") . ".";
                    $trigerred = true;
                }

                // create a file dropbox
                if ($service_id == 5) {
                    Log::info("create a file dropbox");
                    Artisan::call('app:dropbox_create_file_reaction', [
                        'user' => $userId,
                        'reaction' => $reaction->id
                    ]);
                    $description = $description . " create a file dropbox a été déclenchée à " . date("H:i:s") . ".";
                    $trigerred = true;
                }

                // remove a file dropbox
                if ($service_id == 7) {
                    Log::info("remove a file dropbox");
                    Artisan::call('app:dropbox_remove_file_reaction', [
                        'user' => $userId,
                        'reaction' => $reaction->id
                    ]);
                    $description = $description . " remove a file dropbox a été déclenchée à " . date("H:i:s") . ".";
                    $trigerred = true;
                }

                // create a issue
                if ($service_id == 22) {
                    Log::info("create a issue");
                    Artisan::call('app:create_issue', [
                        'user' => $userId,
                        'reaction' => $reaction->id
                    ]);
                    $description = $description . " create a issue a été déclenchée à " . date("H:i:s") . ".";
                    $trigerred = true;
                }

                // rename last file edited
                if ($service_id == 23) {
                    Log::info("rename last file");
                    Artisan::call('app:drive_rename_last_file_reaction', [
                        'user' => $userId,
                        'reaction' => $reaction->id
                    ]);
                    $description = $description . " rename last file a été déclenchée à " . date("H:i:s") . ".";
                    $trigerred = true;
                }

                if ($trigerred) {
                    AreaHistorique::create([
                        'users_id' => $userId,
                        'areas_id' => 0,
                        'name' => $area_name,
                        'description' => $description
                    ]);
                }
            }
        } else {
            Log::error("Action with id {$actionId} not found.");
        }

        return 0;
    }
}
