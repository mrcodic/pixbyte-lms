<?php

use App\Http\Controllers\Api\Parent\AuthController as ParentAuthController;
use App\Http\Controllers\Api\Student\AuthController;
use App\Http\Controllers\Api\Student\HomePageController;
use App\Http\Controllers\Api\Parent\HomePageController as HomePageParentController ;
use App\Http\Controllers\Api\Parent\MainController;
use App\Http\Controllers\Api\Student\MainController as StudentMainController;
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
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::post('/auth/parent/login', [ParentAuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'getProfile']);
    Route::post('/auth/parent/logout', [ParentAuthController::class, 'logout']);
    Route::get('/auth/parent/profile', [ParentAuthController::class, 'getProfile']);
    Route::get('notifications',[HomePageController::class,'get_notifications']);
    Route::get('notifications/parent',[HomePageParentController::class,'get_notifications_parent']);
    Route::post('markAllRead',[HomePageParentController::class,'markAllRead']);
    Route::get('get-classrooms',[MainController::class,'getClassrooms']);
    Route::get('get-rooms/{id}',[MainController::class,'getRooms']);
    Route::get('get-exams/{id}',[MainController::class,'getExams']);
    Route::get('get-homePage',[HomePageParentController::class,'homePage']);

    Route::prefix('students')->group(function (){
        Route::post('markAllRead',[HomePageController::class,'markAllRead']);
        Route::get('get-classrooms',[StudentMainController::class,'getClassrooms']);
        Route::get('get-rooms/{id}',[StudentMainController::class,'getRooms']);
        Route::get('get-exams/{id}',[StudentMainController::class,'getExams']);
        Route::get('get-quiz/{id}',[StudentMainController::class,'getQuiz']);
        Route::get('get-lessons/{id}',[StudentMainController::class,'getLesson']);
        Route::get('get-assignments/{id}',[StudentMainController::class,'getAssignment']);
    });


});
