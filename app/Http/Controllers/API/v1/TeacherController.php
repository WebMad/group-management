<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Teacher\AddRequest;
use App\Http\Requests\API\v1\Teacher\DeleteRequest;
use App\Http\Requests\API\v1\Teacher\UpdateRequest;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Teacher[]|Collection|Response
     */
    public function index()
    {
        return Teacher::all();
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
        Teacher::create([
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'email' => $data['email'],
            'phone' => $data['phone'],
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
        $user = Teacher::find($id);
        $data = $request->all();
        $user->surname = $data['surname'] ?? $user->surname;
        $user->name = $data['name'] ?? $user->name;
        $user->patronymic = $data['patronymic'] ?? $user->patronymic;
        $user->email = $data['email'] ?? $user->email;
        $user->phone = $data['phone'] ?? $user->phone;
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
        Teacher::find($id)->forceDelete();
    }
}
