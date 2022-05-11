<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
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

//Public routes
Route::post('/login', [UserController::class, 'login']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [UserController::class, 'logout']);

    //student
    Route::apiResource('students', StudentController::class);

    //Teachers
    Route::apiResource('teachers', TeacherController::class);

    //Modules
    Route::apiResource('modules', ModuleController::class);

    //Marks
    Route::apiResource('marks', MarkController::class)->except('show');

    //Complaints
    Route::apiResource('complaints', ComplaintController::class);

});


/**
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
**/
