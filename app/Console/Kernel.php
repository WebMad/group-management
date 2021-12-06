<?php

namespace App\Console;

use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule
             ->command('schedule:daily-send')
             ->timezone(new DateTimeZone('+03:00'))
             ->dailyAt('19:00');

         $schedule
             ->command('education-history:create')
             ->sundays()
             ->at('18:00')
             ->timezone('+03:00');

        $schedule
            ->command('attendance:send')
            ->mondays()
            ->at('9:00')
            ->timezone('+03:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
