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

class crypto_reached_price extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crypto_reached_price {user} {action_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'triggered when a crypto attains a choosen price';

    public function get_current_crypto_price($pair)
    {
        $pair_fixed = strtoupper($pair . 'USDT');
        $response = Http::withOptions([
            'verify' => false
        ])->get("https://api.binance.com/api/v3/ticker/price?symbol=$pair_fixed");

        if ($response->failed()) {
            Log::info('Failed to fetch crypto price from Binance.');
            return 1;
        }
        $price = $response->json()['price'];
        return $price;
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

        $crypto_choosed = $action->first_parameter;
        $price_choosed = $action->second_parameter;

        $actual_price = $this->get_current_crypto_price($crypto_choosed);

        if ($actual_price == 1) {
            return 1;
        }

        if ($actual_price >= $price_choosed) {
            Log::info('crypto price reached !' . ' | actual price: ' . $actual_price . ' | price_choosed: ' . $price_choosed);
            $action->second_parameter = null;
            $action->save();
            return 0;
        } else {
            Log::info('crypto price still not reached ! actual price: ' . $actual_price . ' | price_choosed: ' . $price_choosed);
            return 1;
        }
    }

}