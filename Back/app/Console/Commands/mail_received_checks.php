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
            'reactions.service'
        ])->get();
        \Log::info('mail_received_checks: ' . $areas);
        return 0;
    }
}
