<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\SystemSetting\AddRequest;
use App\Http\Requests\API\v1\SystemSetting\UpdateRequest;
use App\Models\SystemSetting;
use App\Repositories\SystemSettingsRepository;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\SystemSetting[]
     */
    public function index()
    {
        return SystemSettingsRepository::getSettings();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddRequest $request
     * @return void
     */
    public function store(AddRequest $request)
    {
        $data = $request->all(['name', 'value']);
        SystemSettingsRepository::setSetting($data['name'], $data['value']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return SystemSetting::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $setting = SystemSetting::find($id);
        $setting->name = $request->get('name');
        $setting->value = $request->get('value');
        $setting->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SystemSetting::find($id)->forceDelete();
    }
}
