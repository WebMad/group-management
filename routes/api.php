<?php

use App\Http\Controllers\API\v1\EduHistoryController;
use App\Http\Controllers\API\v1\ScheduleController;
use App\Http\Controllers\API\v1\StudentController;
use App\Http\Controllers\API\v1\SubjectController;
use App\Http\Controllers\API\v1\SystemSettingController;
use App\Http\Controllers\API\v1\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::apiResource('teacher', TeacherController::class);
        Route::apiResource('subject', SubjectController::class);
        Route::apiResource('schedule', ScheduleController::class);
        Route::apiResource('student', StudentController::class);
        Route::apiResource('system-settings', SystemSettingController::class);
        Route::group(['prefix' => 'history'], function () {
            Route::post('show', [EduHistoryController::class, 'show']);
            Route::post('create', [EduHistoryController::class, 'create']);
            Route::post('delete', [EduHistoryController::class, 'delete']);
            Route::post('show-by-date', [EduHistoryController::class, 'showByDate']);
            Route::post('fill-history', [EduHistoryController::class, 'fillHistory']);
        });
        Route::get('schedule-scheme', [ScheduleController::class, 'scheme'])->name('schedule-scheme');
    });
});
