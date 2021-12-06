<?php

namespace App\Console\Commands;

use App\Mail\AttendanceMail;
use App\Models\EducationHistory;
use App\Models\SessionLog;
use App\Models\Student;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMondayReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправляет понедельничный отчет';

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
        $start_date = new DateTime();
        $start_date->modify('-1 day');

        while ($start_date->format('w') != 1) {
            $start_date->modify('-1 day');
        }

        $end_date = clone $start_date;
        $end_date->modify('+7 day');

        /** @var EducationHistory[] $ehs */
        $ehs = EducationHistory::where([
            ['start_date', $start_date->format("Y-m-d"), '>='],
            ['end_date', $end_date->format("Y-m-d"), '<'],
            'filled' => true,
            'account_hours' => true
        ])->get();

        $hours = 0;
        $hoursByStudent = [];
        foreach ($ehs as $eh) {
            /** @var SessionLog[] $session_logs */
            $session_logs = $eh->sessionLog;
            foreach ($session_logs as $session_log) {
                if (!isset($hoursByStudent[$session_log->student_id])) {
                    $hoursByStudent[$session_log->student_id] = 0;
                }

                if ($session_log->attend == false && $session_log->valid_reason = false) {
                    $hoursByStudent[$session_log->student_id] += 2;
                }
            }
            $hours += 2;
        }

        $studentsToSend = [];
        foreach ($hoursByStudent as $studentId => $studentHours) {
            if ($studentHours > 0 && (100 / $hours) * $studentHours >= 40) {
                $studentsToSend[] = $studentId;
            }
        }

        /** @var Student[] $students */
        $students = Student::where([
            'id' => $studentsToSend
        ])->get();

        $studentsToSend = [];
        foreach ($students as $student) {
            $studentsToSend[] = [
                'name' => $student->fio,
                'percent' => round((100 / $hours) * $hoursByStudent[$student->id], 2)
            ];
        }

        Mail::to('alecmei.gubin@yandex.ru')->send(new AttendanceMail($students));

        return 0;
    }
}
