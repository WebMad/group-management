<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Subject\AddRequest;
use App\Http\Requests\API\v1\Subject\DeleteRequest;
use App\Http\Requests\API\v1\Subject\UpdateRequest;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Teacher[]|Collection|Response
     */
    public function index()
    {
        return Subject::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddRequest $request
     * @return Response
     */
    public function store(AddRequest $request)
    {
        $data = $request->all();
        Subject::create([
            'name' => $data['name'],
            'teacher_id' => $data['teacher_id'],
            'account_hours' => $data['account_hours'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $subject = Subject::find($id);
        $data = $request->all();
        $subject->name = $data['name'] ?? $subject->name;
        $subject->teacher_id = $data['teacher_id'] ?? $subject->teacher_id;
        $subject->account_hours = $data['account_hours'] ?? $subject->account_hours;
        $subject->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(DeleteRequest $request, $id)
    {
        Subject::find($id)->forceDelete();
    }
}
