<?php

namespace App\Console\Commands;

use App\Operations\ScheduleOperation;
use App\Operations\VKAPIOperation;
use DateTime;
use Illuminate\Console\Command;

class SendDailySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daily-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var \App\Models\Schedule[] $schedule */
        $schedule = ScheduleOperation::generateScheduleByDate(new DateTime());

        if (!empty($schedule)) {

            $message = "Расписание на сегодня:\n\n";

            foreach ($schedule as $unit) {
                $start_time = (new DateTime($unit->scheme->start_time))->format('H:i');
                $end_time = (new DateTime($unit->scheme->end_time))->format('H:i');
                $message .= " - {$start_time}-{$end_time} {$unit->subject->name} {$unit->address}\n";
            }

            VKAPIOperation::sendMessageToCommunityChat($message);
        }
        return 0;
    }
}
