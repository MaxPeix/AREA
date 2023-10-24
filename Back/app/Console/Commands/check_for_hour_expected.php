<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Area;
use Illuminate\Support\Facades\Http;
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
    
        if (!$user) {
            Log::error('User not found');
            return 1;
        }

        if (!$user->hour_selected) {
            Log::error('User has not selected an hour');
            return 1;
        }
        $hour_selected = $user->hour_selected;

        $heureParis = Carbon::now('Europe/Paris');
        \Log::info("heureParis: " . $heureParis);
        return 1;

        return 0;
    }

}