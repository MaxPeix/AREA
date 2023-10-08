<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use App\Models\User;

class mail_received_checks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mail_received_checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a mail is received from user where google is validated and have an area validated with an action with name receive a mail and a reaction';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $areas = Area::with([
            'actions.service',
            'reactions.service',
            'areaHistorique'
        ])->get();
        foreach ($areas as $area) {
            \Log::info('Area: ' . $area->name);
            $user = User::find($area->users_id);
            if ($user->google_validated) {
                \Log::info('User: ' . $user->name);
                foreach ($area->actions as $action) {
                    if ($action->name == 'receive a mail') {
                        \Log::info('Action: ' . $action->name);
                        foreach ($area->reactions as $reaction) {
                            if ($reaction->actions_id == $action->id) {
                                \Log::info('Reaction: ' . $reaction->id);
                                $service = Service::find($reaction->services_id);
                                if ($service->working) {
                                    \Log::info('Service: ' . $service->service_name);
                                    $areaHistorique = $area->areaHistorique->last();
                                    if ($areaHistorique->name == 'receive a mail') {
                                        \Log::info('AreaHistorique: ' . $areaHistorique->name);
                                        $this->sendMail($user, $service);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return 0;
    }
}
