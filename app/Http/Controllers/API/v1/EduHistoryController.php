<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\History\FillRequest;
use App\Http\Requests\API\v1\History\ShowByDateRequest;
use App\Http\Requests\API\v1\History\ShowRequest;
use App\Models\EducationHistory;
use App\Models\SessionLog;
use DateTime;
use Illuminate\Http\Request;

class EduHistoryController extends Controller
{
    public function show(ShowRequest $request)
    {
        $edu_history_id = $request->input('edu_history_id');
        return EducationHistory::where('id', $edu_history_id)
            ->with('subject', 'teacher', 'sessionLog')->first();
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
}
