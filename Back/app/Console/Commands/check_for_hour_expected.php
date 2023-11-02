<?php

namespace App\Console\Commands;

use App\Models\Action;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class check_for_hour_expected extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check_for_hour_expected {user} {action_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if hour is reached';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action_id = $this->argument('action_id');
        $action = Action::find($action_id);
        $heureParis = Carbon::now('Europe/Paris');
        
        if (!$action) {
            Log::error('action not found');
            return 1;
        }

        if (!$action->first_parameter) {
            Log::error('User has not selected an hour on the action');
            return 1;
        }
        if ($action->second_parameter && $action->second_parameter == 1) {
            return 1;
        }
        $hour_selected = $action->first_parameter;
        
        // Obtenir l'heure et les minutes de l'heure sélectionnée de l'utilisateur
        list($hour, $minutes) = explode(':', $hour_selected);

        // Obtenir l'heure et les minutes de l'heure actuelle à Paris
        $currentHour = $heureParis->hour;
        $currentMinutes = $heureParis->minute;
        
        // Comparer les heures et les minutes
        if ($currentHour > $hour || ($currentHour == $hour && $currentMinutes >= $minutes)) {
            Log::info('Hour reached  !!!!!!!');
            $action->second_parameter = 1;
            $action->save();
            return 0;
        } else {
            Log::info('Hour not still reached!');
            return 1;
        }
    }

}