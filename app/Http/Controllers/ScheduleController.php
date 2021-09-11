<?php

namespace App\Http\Controllers;

use App\Models\ScheduleScheme;
use App\Operations\ScheduleOperation;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function view()
    {
        return view('schedule.view', [
            'scheme' => ScheduleScheme::orderBy('start_time')->get()
        ]);
    }

    public function generateSchedule()
    {
        return ScheduleOperation::generateScheduleByDate(new \DateTime());
    }
}
