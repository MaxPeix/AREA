<?php

namespace App\Console\Commands;

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
    protected $signature = 'app:check_for_hour_expected {user}';

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
        $userId = $this->argument('user');
        $user = User::find($userId);
        $heureParis = Carbon::now('Europe/Paris');
        
        if (!$user) {
            Log::error('User not found');
            return 1;
        }

        if (!$user->hour_selected) {
            Log::error('User has not selected an hour');
            return 1;
        }
        
        // Obtenir l'heure et les minutes de l'heure sélectionnée de l'utilisateur
        list($hour, $minutes) = explode(':', $user->hour_selected);

        // Obtenir l'heure et les minutes de l'heure actuelle à Paris
        $currentHour = $heureParis->hour;
        $currentMinutes = $heureParis->minute;
        
        // Comparer les heures et les minutes
        if ($currentHour > $hour || ($currentHour == $hour && $currentMinutes >= $minutes)) {
            Log::info('Hour reached  !!!!!!!');
            $user->hour_selected = null;
            $user->save();
            return 0;
        } else {
            Log::info('Hour not still reached!');
            return 1;
        }
    }

}