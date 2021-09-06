<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Schedule\AddRequest;
use App\Http\Requests\API\v1\Schedule\DeleteRequest;
use App\Http\Requests\API\v1\Schedule\UpdateRequest;
use App\Models\Schedule;
use App\Models\ScheduleScheme;
use Illuminate\Http\Response;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Schedule[]
     */
    public function index()
    {
        return Schedule::orderBy('week_type')->with('subject', 'subject.teacher')->get();
    }

    /**
     * @return mixed
     */
    public function scheme()
    {
        return ScheduleScheme::orderBy('start_time')->get();
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
        Schedule::create([
            'subject_id' => $data['subject_id'],
            'scheme_id' => $data['scheme_id'],
            'week_type' => $data['week_type'],
            'day_of_week' => $data['day_of_week'],
            'start_week' => $data['start_week'],
            'end_week' => $data['end_week'],
            'address' => $data['address']
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
        $user = Schedule::find($id);
        $data = $request->all();
        $user->subject_id = $data['subject_id'] ?? $user->subject_id;
        $user->scheme_id = $data['scheme_id'] ?? $user->scheme_id;
        $user->week_type = $data['week_type'] ?? $user->week_type;
        $user->day_of_week = $data['day_of_week'] ?? $user->day_of_week;
        $user->start_week = $data['start_week'] ?? $user->start_week;
        $user->end_week = $data['end_week'] ?? $user->end_week;
        $user->address = $data['address'] ?? $user->address;
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(DeleteRequest $request, $id)
    {
        Schedule::find($id)->forceDelete();
    }
}
