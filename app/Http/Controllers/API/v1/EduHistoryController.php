<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\History\AddRequest;
use App\Http\Requests\API\v1\History\DeleteRequest;
use App\Http\Requests\API\v1\History\FillRequest;
use App\Http\Requests\API\v1\History\ImportFromPrevLessonRequest;
use App\Http\Requests\API\v1\History\ShowByDateRequest;
use App\Http\Requests\API\v1\History\ShowRequest;
use App\Models\EducationHistory;
use App\Models\ScheduleScheme;
use App\Models\SessionLog;
use App\Models\Subject;
use DateTime;

class EduHistoryController extends Controller
{
    public function show(ShowRequest $request)
    {
        $edu_history_id = $request->input('edu_history_id');
        return EducationHistory::where('id', $edu_history_id)
            ->with('subject', 'teacher', 'sessionLog')->first();
    }

    public function create(AddRequest $request)
    {
        $data = $request->input();
        $subject = Subject::find($data['subject_id']);
        $scheme = ScheduleScheme::find($data['scheme_id']);

        $start_date = new DateTime($data['date']);
        $end_date = new DateTime($data['date']);

        $start_time = explode(':', $scheme->start_time);
        $end_time = explode(':', $scheme->end_time);

        $start_date->setTime($start_time[0], $start_time[1]);
        $end_date->setTime($end_time[0], $end_time[1]);

        $edu_history = new EducationHistory();
        $edu_history->subject_id = $data['subject_id'];
        $edu_history->teacher_id = $subject->teacher_id;
        $edu_history->start_date = $start_date->format('Y-m-d H:i:s');
        $edu_history->end_date = $end_date->format('Y-m-d H:i:s');
        $edu_history->save();
    }

    public function importFromPrevLesson(ImportFromPrevLessonRequest $request)
    {
        /** @var EducationHistory $eduHistory */
        $eduHistory = EducationHistory::find($request->input('edu_history_id'));

        /** @var SessionLog[] $logs */
        $logs = $eduHistory->sessionLog;

        $start_date = new DateTime($eduHistory->start_date);

        /** @var EducationHistory[] $records */
        $records = EducationHistory::where('start_date', '>=', $start_date->format("Y-m-d"))
            ->where('end_date', '<=', $eduHistory->start_date)
            ->with('subject', 'teacher', 'sessionLog')
            ->orderBy('start_date', "desc")
            ->get();

        if (!isset($records[0])) {
            return [
                'result' => false,
                'msg' => "Прошлая пара не найдена"
            ];
        }

        $prevLesson = $records[0];

        /** @var SessionLog[] $prevLogs */
        $prevLogs = $prevLesson->sessionLog;

        foreach ($logs as $log) {
            foreach ($prevLogs as $prevLog) {
                if ($log->student_id == $prevLog->student_id) {
                    $log->attend = $prevLog->attend;
                    $log->valid_reason = $prevLog->valid_reason;
                    $log->save();
                    break;
                }
            }
        }

        $eduHistory->filled = true;
        $eduHistory->save();

        return [
            'result' => true
        ];
    }

    public function showByDate(ShowByDateRequest $request)
    {
        $date = new DateTime($request->input('date'));

        return EducationHistory::where('start_date', '>=', $date->format('Y-m-d'))
            ->where('end_date', '<=', $date->modify('+1 day')->format('Y-m-d'))
            ->with('subject', 'teacher', 'sessionLog')
            ->orderBy('start_date')
            ->get();
    }

    public function fillHistory(FillRequest $request)
    {
        $students = $request->input()['students'];
        $edu_history_id = $request->input()['edu_history_id'];

        $history = EducationHistory::find($edu_history_id);
        $history->filled = (bool)$request->input()['filled'];
        $history->account_hours = (bool)$request->input()['account_hours'];
        $history->save();

        foreach ($students as $student_id => $student) {
            SessionLog::updateOrCreate([
                'eh_id' => $edu_history_id,
                'student_id' => $student_id,
            ], [
                'eh_id' => $edu_history_id,
                'student_id' => $student_id,
                'attend' => (bool)$student['attend'],
                'valid_reason' => (bool)$student['valid_reason'],
            ]);
        }
    }

    public function delete(DeleteRequest $request)
    {
        $edu_history = EducationHistory::find($request->input('id'));
        SessionLog::where('eh_id', $request->input('id'))->forceDelete();
        $edu_history->forceDelete();
    }
}
