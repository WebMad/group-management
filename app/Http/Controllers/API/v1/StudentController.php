<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Student\AddRequest;
use App\Http\Requests\API\v1\Student\UpdateRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Student[]
     */
    public function index()
    {
        return Student::orderBy("surname")->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddRequest $request)
    {
        $data = $request->all();
        Student::create([
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'code' => $data['code'],
            'vk_id' => $data['vk_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Student::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return void
     */
    public function update(UpdateRequest $request, $id)
    {
        $student = Student::find($id);
        $data = $request->all();
        $student->surname = $data['surname'] ?? $student->surname;
        $student->name = $data['name'] ?? $student->name;
        $student->patronymic = $data['patronymic'] ?? $student->patronymic;
        $student->email = $data['email'] ?? $student->email;
        $student->phone = $data['phone'] ?? $student->phone;
        $student->code = $data['code'] ?? $student->code;
        $student->vk_id = $data['vk_id'] ?? $student->vk_id;
        $student->is_expelled = $data['is_expelled'];
        $student->date_expelled = $data['is_expelled'] ? $data['date_expelled'] : null;
        $student->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Student::find($id)->forceDelete();
    }
}
