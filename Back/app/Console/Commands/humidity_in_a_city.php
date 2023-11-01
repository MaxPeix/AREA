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

class humidity_in_a_city extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:humidity_in_a_city {user} {action_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'triggered when the humidity in a city is higher than a choosen humidity';

    public function get_humidity_in_a_city($city)
    {
        $response = Http::withOptions([
            'verify' => false
        ])->get("https://wttr.in/$city?format=%h");

        if ($response->status() == 404) {
            return 'City not found';
        }
        
        $humidity = $response->body();

        preg_match('/-?\d+/', $humidity, $matches);

        if (!empty($matches)) {
            $humidity = (int) $matches[0];
            return $humidity;
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
            Log::error('Humidity not found from parameter second_parameter');
            return 1;
        }

        $city_choosed = $action->first_parameter;
        $humidity_choosed = $action->second_parameter;

        $actual_humidity = $this->get_humidity_in_a_city($city_choosed);

        if (!$actual_humidity) {
            Log::error('humidity not found from API');
            return 1;
        }

        if ($actual_humidity >= $humidity_choosed) {
            Log::info('humidity reached ! actual humidity: ' . $actual_humidity . ' | humidity_choosed: ' . $humidity_choosed);
            $action->second_parameter = null;
            $action->save();
            return 0;
        } else {
            Log::info('humidity not reached ! actual humidity: ' . $actual_humidity . ' | humidity_choosed: ' . $humidity_choosed);
            return 1;
        }
    }

}