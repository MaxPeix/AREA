<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class main_to_check_for_all_the_actions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:main_to_check_for_all_the_actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'main who call all the action files to check if an specific action is triggered';

    public function get_all_areas()
    {
        $areas = Area::with([
            'user',
            'actions.service',
            'actions.reactions.service',
        ])->get();
        return $areas;
    }

    public function check_all_the_actions_of_an_specific_area($area, $user)
    {
        foreach ($area->actions as $action) {
            if ($action->activated == false || !$action->service) {
                continue;
            }
            // voir si un mail a été recu
            if ($action->service->id == 2) {
                $exitCode = Artisan::call('app:mail_received_checks', [
                    'user' => $user->id,
                ]);

                if ($exitCode === 0) {
                    Log::info("un mail a été recu pour l'user" . $user->id);
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }
            // voir si le nombre de follower a changé
            if ($action->service->id == 16) {
                $exitCode = Artisan::call('app:spotify_follower_count_change_check', [
                    'user' => $user->id,
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }
            // voir si le nombre de follower a changé
            if ($action->service->id == 18) {
                $exitCode = Artisan::call('app:check_for_hour_expected', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }

            // voir si il y a un nouveau commit
            if ($action->service->id == 19) {
                $exitCode = Artisan::call('app:check_for_new_commit', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }

            // voir si un fichier a été modifié sur dropbox
            if ($action->service->id == 3) {
                $exitCode = Artisan::call('app:check_for_file_changed_on_dropbox', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }

            // voir si il y a un nouvel issue
            if ($action->service->id == 20) {
                $exitCode = Artisan::call('app:check_for_new_issue', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }

            // voir si un prix de crypto a été atteint
            if ($action->service->id == 24) {
                $exitCode = Artisan::call('app:crypto_reached_price', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }

            // voir si une température a été atteinte
            if ($action->service->id == 25) {
                $exitCode = Artisan::call('app:temperature_reached_meteo_in_a_city', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }

            // voir si il pleut dans la ville
            if ($action->service->id == 26) {
                $exitCode = Artisan::call('app:humidity_in_a_city', [
                    'user' => $user->id,
                    'action_id' => $action->id
                ]);

                if ($exitCode === 0) {
                    Artisan::call('app:main_to_execute_reactions', [
                        'action' => $action->id,
                        'user' => $user->id,
                        'area_name' => $area->name
                    ]);
                }
            }
        }  
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $areas = $this->get_all_areas();

        foreach ($areas as $area) {
            if ($area->activated == false) {
                continue;
            }
            $user = User::find($area->users_id);
            if (!$user) {
                Log::info("User introuvable sur l'area " . $area->id);
                continue;
            }
            $this->check_all_the_actions_of_an_specific_area($area, $user);
        }
    }
}
