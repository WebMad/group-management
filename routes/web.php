<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\VKAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/schedule', [ScheduleController::class, 'view'])->name('schedule');
    Route::get('/schedule-generate', [ScheduleController::class, 'generateSchedule'])->name('schedule-generate');
    Route::get('/teachers', [TeacherController::class, 'view'])->name('teachers');
    Route::get('/subjects', [SubjectController::class, 'view'])->name('subjects');
    Route::get('/students', [StudentController::class, 'view'])->name('students');
    Route::get('/system-settings', [SystemSettingController::class, 'view'])->name('system-settings');
});

Route::get('/vk-api', [VKAPIController::class, 'route']);
Route::get('/vk-api/send', [VKAPIController::class, 'sendSchedule']);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
