<?php

namespace App\Console;

use App\Operations\ScheduleOperation;
use App\Operations\VKAPIOperation;
use DateTime;
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
         $schedule->call(function () {
             /** @var \App\Models\Schedule[] $schedule */
             $schedule = ScheduleOperation::generateScheduleByDate(new DateTime());

             $message = "Расписание на сегодня:\n\n";

             foreach ($schedule as $unit) {
                 $start_time = (new DateTime($unit->scheme->start_time))->format('H:i');
                 $end_time = (new DateTime($unit->scheme->end_time))->format('H:i');
                 $message .= " - {$start_time}-{$end_time} {$unit->subject->name} \n";
             }

             VKAPIOperation::sendMessageToCommunityChat($message);
         })->timezone(new DateTimeZone('+03:00'))->dailyAt('21:45');
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
