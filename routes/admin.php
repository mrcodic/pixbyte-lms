 <?php

use App\Http\Controllers\Admin\AdminController;
 use App\Http\Controllers\Admin\AttendanceController;
 use App\Http\Controllers\Admin\AuthLogsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClassRoomsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LessonsController;
use App\Http\Controllers\Admin\PointSettingsController;
use App\Http\Controllers\Admin\QuestionBankController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GiftController;

 use App\Http\Controllers\CouponController;
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
Route::post('logout' ,function (){
    auth('admin')->logout();
    return redirect()->route('getAdminLogin');
} )->name('admin.logout');
/* Route Dashboards */
Route::group(['middleware'=>'guest:admin'],function (){
    Route::get('/login','App\Http\Controllers\AdminController@login')->name('getAdminLogin')->middleware('NotAdminMiddleware');
    Route::post('logins','App\Http\Controllers\AdminController@logins')->name('adminLogin');
});
Route::middleware([ 'auth:admin','NotAdminMiddleware'])->group( function () {
    Route::get('forceLogout/{id}',[StudentController::class,'forceLogout']);
    Route::post('forceLogout_selected/',[StudentController::class,'forceLogout_selected']);

    Route::get('dashboard', [\App\Http\Controllers\AdminController::class, 'dashboardAnalytics'])->name('dashboard-analytics');
    Route::get('/get_users',[UserController::class,'get_users'])->name('get_users');
    Route::get('/get_students_users',[StudentController::class,'get_students_users'])->name('get_students_users');
    Route::resource('admins',AdminController::class);
    Route::get('/get_admins',[AdminController::class,'get_admins'])->name('get_admins');
    Route::get('/profile-edit', [AdminController::class, 'edit_profile'])->name('profile.edit');
    Route::post('/profile-update', [AdminController::class, 'update_profile'])->name('profile.update');

    Route::resource('users',UserController::class);
    Route::resource('students',StudentController::class);
    Route::post('/add_classroom_to_student',[StudentController::class,'addClassRoomToStudent'])->name('admin.add_classroom_to_student');
    Route::resource('roles',RoleController::class);
    Route::get('/getPermessions/{type}',[RoleController::class,'get_permissions'])->name('get_permissions');
    Route::get('/getRole/{id}',[RoleController::class,'get_role'])->name('get_role');
    Route::get('/dashboard/coupons',[DashboardController::class,'getCoupons'])->name('get.coupons');
    Route::get('/dashboard/fitterChart',[DashboardController::class,'fitterChart'])->name('get.fitterChart');
    Route::get('/dashboard/get_students_coupons',[DashboardController::class,'get_students_coupons'])->name('get_students_coupons');
    Route::get('/delete-device',[StudentController::class,'delete_device']);
    Route::get('/get_students_auth_logs',[StudentController::class,'authenticateLogs'])->name('get_students_auth_logs');
    Route::get('/getClassroomFromInstructors/{user}',[StudentController::class,'get_classroom_from_instructors'])->name('admin.get_classroom_from_instructors');
    Route::get('get-student-classrank-data',[StudentController::class,'get_classrank_data'])->name('get-student-classrank-data');
    Route::get('get-student-classrank',[StudentController::class,'get_classrank']);

    Route::get('/point/settings',[PointSettingsController::class,'index'])->name('point.settings');
    Route::post('/point/settings',[PointSettingsController::class,'edit'])->name('point.settings.edit');
    Route::get('get-room_student-data',[StudentController::class,'get_room_student_data'])->name('get-room_student-data-admin');
    Route::get('get-quiz_student-data',[StudentController::class,'get_quiz_student_data'])->name('get-quiz_student-data-admin');
    Route::get('getPoint/{id}',[StudentController::class,'getPoint'])->name('get-point-data');
    Route::post('/resetCoupon', [CouponController::class,'resetCoupon'])->name('resetCoupon');
    Route::get('/UnBlock', [StudentController::class,'unBlock']);
    Route::post('/changeBlock', [StudentController::class,'changeBlock']);
    Route::get('make_first_attendance/',[AttendanceController::class,'make_first_attendance']);
    Route::get('make_second_attendance/',[AttendanceController::class,'make_second_attendance']);
    Route::get('changeAttendanceStatus',[AttendanceController::class,'changeAttendanceStatus']);
    Route::prefix('/classrooms')->group( function () {
        Route::get('/',[ClassRoomsController::class,'index'])->name('admin.classrooms');
        Route::get('/get_classrooms',[ClassRoomsController::class,'get_classrooms'])->name('admin.get_classrooms');
        Route::post('/classrooms',[ClassRoomsController::class,'store'])->name('admin.classrooms.create');
        Route::delete('/classrooms/delete/{id}',[ClassRoomsController::class,'destroy'])->name('admin.classrooms.delete');
        Route::get('/classrooms/edit/{id}',[ClassRoomsController::class,'edit'])->name('admin.classrooms.edit');
        Route::post('/classrooms/edit/{id}',[ClassRoomsController::class,'update'])->name('admin.classrooms.update');
        Route::post('/classrooms/set_demo',[ClassRoomsController::class,'set_demo'])->name('admin.classrooms.setdemo');

    });
    Route::prefix('/rooms')->group( function () {
        Route::get('list/{class?}',         [RoomsController::class,'index'])->name('admin.classroom.show');
        Route::get('/get_rooms/{class?}',   [RoomsController::class,'get_rooms'])->name('admin.get_rooms');
        Route::get('/get_lessons/{room}',   [RoomsController::class,'get_lessons'])->name('admin.classroom.lessons');
        Route::post('room/create',          [RoomsController::class,'store'])->name('admin.rooms.create');
        Route::post('room/upload_material', [RoomsController::class,'uploadMaterial'])->name('admin.room.upload_material');
        Route::delete('/room/delete/{id}',  [RoomsController::class,'destroy'])->name('admin.room.delete');
        Route::get('/room/edit/{id}',       [RoomsController::class,'edit'])->name('admin.room.edit');
        Route::post('/room/edit/{room}',    [RoomsController::class,'update'])->name('admin.room.update');
        Route::get('/classroomFromInstructors',    [RoomsController::class,'classroomFromInstructors'])->name('admin.get_classroom_instructors');
    });

    Route::get('lessons/list/{room?}',  [LessonsController::class,'index'])->name('admin.room.lessons');
    Route::get('/get_rooms/{room?}',    [LessonsController::class,'get_lessons'])->name('admin.get_lessons');
    Route::post('lesson/create',        [LessonsController::class,'store'])->name('admin.lesson.create');
    Route::get('/lesson/edit/{id}',     [LessonsController::class,'edit'])->name('admin.lesson.edit');
    Route::get('/lesson/show/{id}',     [LessonsController::class,'show'])->name('admin.lesson.show');
    Route::delete('/lesson/delete/{id}',[LessonsController::class,'destroy'])->name('admin.lesson.delete');
    Route::post('/lesson/edit/{lesson}',[LessonsController::class,'update'])->name('admin.lesson.update');
    Route::get('/roomFromInstructors',  [LessonsController::class,'roomFromInstructors'])->name('admin.get_room_instructors');

    Route::post('parent/pass',          [StudentController::class, 'parentPass'])->name('parent.pass');
    Route::put('parent/edit/{parent}',  [StudentController::class, 'parentUpdate'])->name('parent.update');
    Route::delete('/delete-all-student',[StudentController::class, 'deleteAllStudent'])->name('parent.delete-all-student');

    Route::get('/logins/logs',  [AuthLogsController::class, 'index'])->name('logins.logs');
    Route::get('/get_auth_logs',[AuthLogsController::class, 'get_authenticate_logs'])->name('get_auth_logs');



    Route::prefix('/category')->group( function () {
        Route::get('list/',             [CategoryController::class,'index'])->name('admin.category.list');
        Route::get('get_categories/',   [CategoryController::class,'get_category_data'])->name('admin.get_categories');
        Route::post('/create',          [CategoryController::class,'store'])->name('admin.category.create');
        Route::post('/edit/{category}', [CategoryController::class,'update'])->name('admin.category.edit');
        Route::delete('/{category}',    [CategoryController::class,'destroy'])->name('admin.category.delete');
    });

    Route::prefix('/subcategory')->group( function () {
        Route::get('list/',                 [SubCategoryController::class,'index'])->name('admin.subcategory.list');
        Route::get('get_subcategories/',    [SubCategoryController::class,'get_subcategory_data'])->name('admin.get_subcategories');
        Route::post('/create',              [SubCategoryController::class,'store'])->name('admin.subcategory.create');
        Route::post('/edit/{subcategory}',  [SubCategoryController::class,'update'])->name('admin.subcategory.edit');
        Route::delete('/{subcategory}',     [SubCategoryController::class,'destroy'])->name('admin.subcategory.delete');
    });

    Route::prefix('/question-bank')->group( function () {
        Route::get('list/',                 [QuestionBankController::class,'index'])->name('admin.question-bank.list');
        Route::get('get_question_bank_data',[QuestionBankController::class,'get_question_bank_data'])->name('admin.get_question_bank_data');
        Route::get('/create',               [QuestionBankController::class,'create'])->name('admin.question_bank.create');
        Route::get('get_sub_category_grade/{id}',[QuestionBankController::class,'get_sub_category_grade'])->name('admin.get_sub_category_grade');
        Route::get('get_question_in_bank_data',  [QuestionBankController::class,'get_question_in_bank_data'])->name('admin.get_question_in_bank_data');
        Route::post('/create',                   [QuestionBankController::class,'store'])->name('admin.question-bank.store');
        Route::get('/edit/{id}',                 [QuestionBankController::class,'edit'])->name('admin.question-bank.edit');
        Route::post('update/{questionBank}',     [QuestionBankController::class,'update'])->name('admin.question-bank.update');
        Route::delete('/{questionBank}',         [QuestionBankController::class,'destroy'])->name('admin.question-bank.delete');
    });

    Route::prefix('/question')->group( function () {
        Route::get('list/',                [QuestionController::class,'index'])->name('admin.question.list');
        Route::get('get_question_data',    [QuestionController::class,'get_question_data'])->name('admin.get_question_data');
        Route::get('/create',              [QuestionController::class,'create'])->name('admin.question.create');
        Route::post('/create',             [QuestionController::class,'store'])->name('admin.question.store');
        Route::post('/uploadQuestionImage',[QuestionController::class,'uploadImageQues'])->name('admin.question.uploadImage');
        Route::get('/edit/{question}',     [QuestionController::class,'edit'])->name('admin.question.edit');
        Route::post('/edit/{question}',    [QuestionController::class,'update'])->name('admin.question.update');
        Route::delete('delete/{question}', [QuestionController::class,'destroy'])->name('admin.question.delete');
    });

    Route::prefix('/quiz')->group( function () {
        Route::get('list/',        [QuizController::class,'index'])->name('admin.quiz.list');
        Route::get('/get_quizs',   [QuizController::class,'get_quiz_data'])->name('admin.get_quizs');
        Route::get('/create',      [QuizController::class,'create'])->name('admin.quiz.create');
        Route::post('/store',      [QuizController::class,'store'])->name('admin.quiz.store');
        Route::get('/edit/{quiz}', [QuizController::class,'edit'])->name('admin.quiz.edit');
        Route::delete('delete/{quiz}',      [QuizController::class,'destroy'])->name('admin.quiz.delete');
        Route::post('update/{quiz}',        [QuizController::class,'update'])->name('admin.quiz.update');
        Route::get('get_room/{grade}',      [QuizController::class,'get_room'])->name('admin.get_room.quiz');
        Route::get('get_classroom/{grade}', [QuizController::class,'get_classroom'])->name('admin.get_classroom.quiz');
        Route::get('getQuestionBank/',      [QuizController::class,'get_questionBank'])->name('admin.get_questionBank');
        Route::get('get-question-in-bank-data-quiz/',[QuizController::class,'get_questionBank_quiz'])->name('admin.get_questionBank_quiz');

    });

    Route::prefix('/gifts')->group( function () {
        Route::get('list/',        [GiftController::class,'index'])->name('admin.gift.list');
        Route::get('/get_gifts',   [GiftController::class,'get_gift_data'])->name('admin.get_gifts');
        Route::get('/get-redemptions-data',   [GiftController::class,'get_redemptions_data'])->name('admin.redemptions');
        Route::get('/create',      [GiftController::class,'create'])->name('admin.gift.create');
        Route::post('/store',      [GiftController::class,'store'])->name('admin.gift.store');
        Route::post('/edit/{gift}',[GiftController::class,'update'])->name('admin.gift.edit');
        Route::delete('{gift}',    [GiftController::class,'destroy'])->name('admin.gift.delete');
        Route::post('/setting',    [GiftController::class,'setting'])->name('admin.gift.setting');
        Route::get('/redemptions',      [GiftController::class,'redemptions']);

    });

    Route::resource('attendance',AttendanceController::class);
    Route::get('get_instructor_with_class/{id}',[AttendanceController::class,'get_instructor_with_class']);
    Route::get('get_room_with_class/{id}',[AttendanceController::class,'get_room_with_class']);
    Route::get('make_room_check',[AttendanceController::class,'make_room_check']);
    Route::get('get-student-attendance-data',[AttendanceController::class,'get_student_attendance_data'])->name('admin.get-student-attendance-data');
    Route::post('changeStatus',[AttendanceController::class,'changeStatus']);
    Route::get('collect_absence',[AttendanceController::class,'collect_absence']);


    // Route::post('/point/settings',[PointSettingsController::class,'edit'])->name('point.settings.edit');
});
