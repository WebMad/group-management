<?php

namespace App\Console\Commands;

use App\Models\EducationHistory;
use App\Operations\ScheduleOperation;
use DateTime;
use Illuminate\Console\Command;

class CreateEducationHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'education-history:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создает запись в таблице education_history о парах сегодня';

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
     * @throws \Exception
     */
    public function handle()
    {
        $schedule = ScheduleOperation::generateScheduleByDate(new DateTime());
        if (!empty($schedule)) {
            foreach ($schedule as $unit) {
                $edu_history = new EducationHistory();
                $edu_history->subject_id = $unit->subject_id;
                $time_start = explode(':', $unit->scheme->start_time);
                $edu_history->start_date = (new DateTime())->setTime($time_start[0], $time_start[1], $time_start[2]);
                $time_end = explode(':', $unit->scheme->end_time);
                $edu_history->end_date = (new DateTime())->setTime($time_end[0], $time_end[1], $time_end[2]);
                $edu_history->teacher_id = $unit->subject->teacher_id;
                $edu_history->save();
            }
        }
        return 0;
    }
}
