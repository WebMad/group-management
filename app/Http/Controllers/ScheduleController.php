<?php

namespace App\Http\Controllers;

use App\Models\ScheduleScheme;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function view()
    {
        return view('schedule.view', [
            'scheme' => ScheduleScheme::orderBy('start_time')->get()
        ]);
    }
}
