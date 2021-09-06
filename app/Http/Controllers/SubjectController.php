<?php

namespace App\Http\Controllers;

use App\Models\Subject;

class SubjectController extends Controller
{
    public function view()
    {
        return view('subject.view', [
            'subjects' => Subject::all()
        ]);
    }
}
