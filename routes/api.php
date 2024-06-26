<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'api';
});

Route::resource('student',App\Http\Controllers\StudentController::class);
Route::resource('group',App\Http\Controllers\GroupController::class);
Route::get('group/study_plan/{id}', [App\Http\Controllers\GroupController::class, 'StudyPlan']);
Route::post('group/study_plan/{id}', [App\Http\Controllers\GroupController::class, 'StudyPlanUpdate']);
Route::resource('lecture',App\Http\Controllers\LectureController::class);
