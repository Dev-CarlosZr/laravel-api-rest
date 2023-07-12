<?php

namespace App\Console;

use App\Jobs\LoginToCitoLab;
use App\Jobs\LoginToCitoLabJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(new LoginToCitoLabJob())->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected $commands = [
        Commands\LoginToCitoLabCommand::class,
    ];
    
    protected function commands()
    {
        
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
