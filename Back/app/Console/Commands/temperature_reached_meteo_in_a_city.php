<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Action;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class temperature_reached_meteo_in_a_city extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temperature_reached_meteo_in_a_city {user} {action_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'triggered when a temperature attains a choosen temperature';

    public function get_temperature_in_a_city($city)
    {
        $response = Http::withOptions([
            'verify' => false
        ])->get("https://wttr.in/$city?format=%t");

        if ($response->status() == 404) {
            return 'City not found';
        }
        
        $temperatureStr = $response->body();

        preg_match('/-?\d+/', $temperatureStr, $matches);

        if (!empty($matches)) {
            $temperature = (int) $matches[0];
            return $temperature;
        } else {
            return false;
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $user = User::find($userId);
        $action_id = $this->argument('action_id');
        $action = Action::find($action_id);

        if (!$user) {
            Log::error('User not found');
            return 1;
        }

        if (!$action) {
            Log::error('Action not found');
            return 1;
        }

        if (!$action->first_parameter) {
            Log::error('City not found from parameter first_parameter');
            return 1;
        }

        if (!$action->second_parameter) {
            Log::error('Temperature not found from parameter second_parameter');
            return 1;
        }

        $city_choosed = $action->first_parameter;
        $temperature_choosed = $action->second_parameter;

        $actual_temperature = $this->get_temperature_in_a_city($city_choosed);

        if (!$actual_temperature) {
            Log::error('Temperature not found from API');
            return 1;
        }

        if ($actual_temperature >= $temperature_choosed) {
            Log::info('temperature reached ! actual temperature: ' . $actual_temperature . ' | temperature_choosed: ' . $temperature_choosed);
            $action->second_parameter = null;
            $action->save();
            return 0;
        } else {
            Log::info('temperature not reached ! actual temperature: ' . $actual_temperature . ' | temperature_choosed: ' . $temperature_choosed);
            return 1;
        }
    }

}