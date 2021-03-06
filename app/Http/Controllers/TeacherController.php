<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function view()
    {
        return view('teacher.view', [
            'teachers' => Teacher::all()
        ]);
    }
}
