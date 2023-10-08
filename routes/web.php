<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentProfile;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserSettings;
use App\Http\Controllers\ClassroomSettings;
use App\Http\Controllers\Parent\AuthController;
use App\Http\Controllers\Parent\ParentController;
use App\Http\Controllers\Parent\ParentSettingsController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StoreController;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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


Route::get('/', function () {
    return view('welcome');
});
Route::get('/privacy', function () {
    return view('privacy');
});
// Route::get('/quiz', function () {
//    return view('quiz.exam');
// });
Route::get('/lesson-locked', function () {
    return view('lesson-locked');
});
Route::post('unlock-code',[CouponController::class,'unlock_code'])->name('room.unloack.code');

Route::middleware(['auth', 'verified','CheckUserCookies'])->group(function () {
    Route::get('/settings/account',[UserSettings::class,'show'])->name('settings')->middleware('DeleteDeviceUser');
    Route::post('/settings/account',[UserSettings::class,'update'])->name('update.settings')->middleware('DeleteDeviceUser');
    // Why do you remove it?
    Route::get('/u', [StudentProfile::class, 'memberShowRedirect'])->middleware('DeleteDeviceUser');
    Route::get('/u/{name_id?}/', [StudentProfile::class, 'membershipShow'])->name('dashboard')->middleware('DeleteDeviceUser');
    Route::get('/get-activity-data', [StudentProfile::class, 'getActivityData'])->name('get-activity-data');
    Route::post('/user/upload-profile',[UserSettings::class,'uploadProfile'])->name('user.profile.croup');
    Route::post('/user/checkUpload-profile',[UserSettings::class,'checkUpload'])->name('user.check.profile.croup');
    Route::post('unlock-code',[CouponController::class,'unlock_code'])->name('room.unloack.code');
    Route::resource('/room', RoomController::class)->middleware(['StudentUsedCoupon','DeleteDeviceUser']);
    Route::get('classroom/{id}/classwork',[ClassroomSettings::class,'getRooms'])->middleware(['ClassRoomMiddleware','DeleteDeviceUser'])->name('classroom.classwork');
    Route::get('classroom/{id}/updates',[ClassroomSettings::class,'getUpdates'])->middleware(['ClassRoomMiddleware','DeleteDeviceUser']);
    Route::get('classroom/{id}/myWork',[ClassroomSettings::class,'getMyWork'])->middleware(['ClassRoomMiddleware','DeleteDeviceUser']);
    Route::get('classroom/{id}/classRank',[ClassroomSettings::class,'getClassRank'])->middleware(['ClassRoomMiddleware','DeleteDeviceUser']);
    Route::get('get-mywork-data',[ClassroomSettings::class,'get_mywork_data'])->name('get-mywork-data');
    Route::get('get-classrank-data',[ClassroomSettings::class,'get_classrank_data'])->name('get-classrank-data');
    Route::get('get_student_use_id',[ClassroomSettings::class,'get_student_use_id'])->name('get_student_use_id');
    Route::get('add-student-in-classroom',[ClassroomSettings::class,'add_student_in_classroom'])->name('add-student-in-classroom');

    Route::resource('quiz', QuizController::class);
    Route::get('get-question-quiz/{id}',[QuizController::class,'get_questions_quiz'])->name('get_questions_quiz');
    Route::get('reset-quiz',[QuizController::class,'reset_quiz'])->name('reset_quiz');
    Route::delete('/delete-all-quiz', [QuizController::class, 'delete_selected'])->name('delete_all_questions');
    Route::get('assignment/{quiz}',[QuizController::class,'get_assignment'])->name('get_assignment');

    Route::get('get_room/{grade}',[QuizController::class,'get_room'])->name('get_room');
    Route::get('get_classroom/{grade}',[QuizController::class,'get_classroom'])->name('get_classroom');
    Route::get('getQuestionBank/',[QuizController::class,'get_questionBank'])->name('get_questionBank');
    Route::get('get-question-in-bank-data-quiz/',[QuizController::class,'get_questionBank_quiz'])->name('get_questionBank_quiz');
    Route::get('/get_answer/{que}',[QuizController::class,'get_answer']);
    Route::get('get-quiz-data',[QuizController::class,'get_quiz_data'])->name('get-quiz-data');
    Route::delete('/delete-all-quiz', [QuizController::class, 'delete_selected'])->name('delete_all_quiz');
    Route::post('/quiz-store', [QuizController::class, 'save_quiz'])->name('quiz.answer.store');
    Route::get('/quiz/{quiz}/show_answer', [QuizController::class, 'show_answer_quiz'])->name('quiz.show.answer')->middleware('ShowAnswer');
    Route::get('/quiz/{quiz}/student_answer', [QuizController::class, 'student_answer_quiz'])->name('quiz.answer_student.answer');
    Route::get('/my-announcement', [AnnouncementController::class, 'my_announcement'])->name('my-announcement');
    Route::get('/clear-announcement',[AnnouncementController::class,'markAllRead']);

    Route::get('/store',[StoreController::class,'index'])->name('store.index');
    Route::get('/my_fav_store',[StoreController::class,'my_fav_store'])->name('my_fav_store');
    Route::get('/my_redemptions_store',[StoreController::class,'my_redemptions_store'])->name('my_redemptions_store');
    Route::post('/add-redemptions',[StoreController::class,'add_redemptions'])->name('add-redemptions');
    Route::post('/add-store',[StoreController::class,'add_store'])->name('add-store');


    Route::get('/instructor',[StudentProfile::class,'instructor_page'])->name('instructor.index');

});
Route::middleware(['auth:web', 'verified','Instructor','CheckUserCookies'])->group(function (){
    // Why do you remove it?
    // Route::get('/u', [StudentProfile::class, 'memberShowRedirect']);
    Route::get('/dashboard', [StudentProfile::class, 'memberShowRedirect'])->name('dashboard.instructor');
    Route::get('/myclassrooms',[ClassroomSettings::class, 'instructor'])->name('myclassrooms.index');
    Route::get('/rooms-by-classroom-id/{id}',[ClassroomSettings::class,'getRoomByClassRoomId'])->name('getRoomByClassRoomId');

    Route::post('/room/existing', [RoomController::class,'room_existing'])->name('room.existing');
    Route::get('get-room-data',[RoomController::class,'get_room_data'])->name('get-room-data');
    Route::post('/move-room-to-classes',[RoomController::class,'room_to_classes']);
    Route::delete('/delete-all-room', [RoomController::class, 'delete_selected'])->name('delete_all_room');
    Route::post('/order-room',[RoomController::class, 'order_room']);
    Route::post('/uploadMaterial',[RoomController::class, 'uploadMaterial']);

    Route::post('/deleteImage/{id}',[RoomController::class, 'deleteImage']);
    Route::get('/publish-room/{id}',[RoomController::class,'publish_room']);


    Route::resource('/lessons', LessonController::class);
    Route::resource('/announcement', AnnouncementController::class);
    Route::post('/uploadMaterialAnnouncement',[AnnouncementController::class, 'uploadMaterial']);
    Route::post('/deleteImageAnnouncement/{id}',[AnnouncementController::class, 'deleteImage']);

    Route::get('get-announcement-data',[AnnouncementController::class,'get_announcement_data'])->name('get-announcement-data');
    Route::delete('/delete-all-announcement', [AnnouncementController::class, 'delete_selected']);

    Route::get('get-lesson-data',[LessonController::class,'get_lesson_data'])->name('get-lesson-data');
    Route::delete('/delete-all-lesson', [LessonController::class, 'delete_selected'])->name('delete_all_lesson');
    Route::post('/lesson/existing', [LessonController::class,'lesson_existing'])->name('lesson.existing');
    Route::post('/order-lesson',[LessonController::class, 'order_lesson']);
    Route::get('/publish-lesson/{id}',[LessonController::class,'publish_lesson']);
    Route::post('/move-lesson-to-rooms',[LessonController::class,'lesson_to_rooms']);


    //Route::get('/get-rooms-class', [LessonController::class,'getRoomsByClass'])->name('get_room_by_class_room');

    Route::resource('/coupon',CouponController::class);
    Route::get('get-coupon-data',[CouponController::class,'get_coupon_data'])->name('get-coupon-data');
    Route::delete('/delete-all-coupon', [CouponController::class,'delete_selected'])->name('delete_all_coupon');
    Route::post('/generate_coupons', [CouponController::class,'generate_coupons'])->name('generate_coupons');
    Route::post('/resetCoupon', [CouponController::class,'resetCoupon'])->name('resetCoupon');
    Route::get('coupon-export',[CouponController::class,'export_coupon'])->name('export-coupon');

    Route::resource('/question', QuestionController::class);
    Route::post('/uploadQuestionImage', [QuestionController::class,'uploadImageQues']);
    Route::get('get-question-data',[QuestionController::class,'get_question_data'])->name('get-question-data');
    Route::get('gradeCategory/{id}',[QuestionController::class,'gradeCategory']);
    Route::get('gradeSubCategory/{id}',[QuestionController::class,'gradeSubCategory']);
    Route::post('saveCat/',[QuestionController::class,'saveCat']);
    Route::delete('/delete-all-questions', [QuestionController::class, 'delete_selected'])->name('delete_all_questions');

    Route::resource('/question-bank',QuestionBankController::class);
    Route::get('get-question-bank-data',[QuestionBankController::class,'get_question_bank_data'])->name('get-question-bank-data');
    Route::get('get-question-in-bank-data',[QuestionBankController::class,'get_question_in_bank_data'])->name('get_question_in_bank_data');
    Route::get('/get_category_grade/{id}',[QuestionBankController::class,'get_category_grade']);
    Route::get('/get_sub_category_grade',[QuestionBankController::class,'get_sub_category_grade']);
    Route::get('/get_questions/',[QuestionBankController::class,'get_questions']);
    Route::delete('/delete-all-question-bank', [QuestionBankController::class, 'delete_selected'])->name('delete-all-question-bank');

    Route::resource('/category',CategoryController::class);
    Route::get('get-category-data',[CategoryController::class,'get_category_data'])->name('get-category-data');
    Route::delete('/delete-all-category', [CategoryController::class, 'delete_selected'])->name('delete_all_category');

    Route::resource('/subcategory',SubCategoryController::class);
    Route::get('get-subcategory-data',[SubCategoryController::class,'get_subcategory_data'])->name('get-subcategory-data');
    Route::delete('/delete-all-subcategory', [SubCategoryController::class, 'delete_selected'])->name('delete_all_subcategory');

    Route::resource('student', StudentController::class);
    Route::get('get-student-data',[StudentController::class,'get_student_data'])->name('get-student-data');
    Route::get('get-room_student-data',[StudentController::class,'get_room_student_data'])->name('get-room_student-data');
    Route::delete('/delete-all-student', [StudentController::class, 'delete_selected'])->name('delete_all_student');
    Route::get('/UnBlock', [StudentController::class,'unBlock']);
    Route::get('get-quiz_student-data',[StudentController::class,'get_quiz_student_data'])->name('get-quiz_student-data');

    Route::resource('attendance', AttendanceController::class);
    Route::get('activity-log', [AttendanceController::class,'get_activity'])->name('activity.log.attendance');
    Route::get('get-student-attendance-data',[AttendanceController::class,'get_student_attendance_data'])->name('get-student-attendance-data');
    Route::get('get-logs-attendance-data',[AttendanceController::class,'get_logs_attendance_data'])->name('get-logs-attendance-data');
    Route::get('get_room_with_class/{id}',[AttendanceController::class,'get_room_with_class']);
    Route::get('get_quiz_with_class/{id}',[AttendanceController::class,'get_quiz_with_class']);
    Route::get('make_first_attendance/',[AttendanceController::class,'make_first_attendance']);
    Route::get('make_second_attendance/',[AttendanceController::class,'make_second_attendance']);
    Route::get('make_room_check',[AttendanceController::class,'make_room_check']);
    Route::get('collect_absence',[AttendanceController::class,'collect_absence']);
    Route::post('changeStatus',[AttendanceController::class,'changeStatus']);
    Route::post('/change-status-all-student', [AttendanceController::class, 'change_status_selected']);
    Route::get('get_student_scan',[AttendanceController::class,'get_student_scan'])->name('get_student_scan');
    Route::get('myassistant/',[UserSettings::class,'my_assistant'])->name('myassistant');
});

Route::get('/clear-notification',[NotificationController::class,'markAllRead']);
Route::get('/loadmore-notification',[NotificationController::class,'loadmore_notification']);
Route::resource('classrooms', ClassroomSettings::class)->middleware(['DeleteDeviceUser','auth']);
Route::post('/uploadCover',[ClassroomSettings::class, 'uploadCover']);
Route::post('/deleteCover/{id}',[ClassroomSettings::class, 'deleteCover']);

Route::get('/make-complete/{room}/{lesson}',[RoomController::class,'make_complete'])->middleware(['DeleteDeviceUser','auth']);
Route::get('/get_classroom_student',[ClassroomSettings::class,'get_classroom_student']);
Route::get('/enter-class-room/{id}',[ClassroomSettings::class,'enter_class_room'])->middleware(['DeleteDeviceUser','auth']);
Route::get('/publish-class-room/{id}',[ClassroomSettings::class,'publish_class_room']);
Route::get('/get-request-student/{id}',[StudentController::class,'get_request_student']);
Route::get('/get_classes_student/{id}',[StudentController::class,'get_classes_student']);
Route::post('/save_instuctor_request',[StudentController::class,'save_instuctor_request'])->middleware(['DeleteDeviceUser','auth']);
Route::get('/get_another_classes_student/{id}/{student_id}',[StudentController::class,'get_another_classes_student']);
Route::post('/replay_request',[StudentController::class,'replay_request']);
Route::get('/getMatrial/{id}',[RoomController::class, 'getMatrial']);

Route::get('/parent-portal', [AuthController::class,'login'])->name('getParentLogin');

Route::post('logins',[AuthController::class,'logins'])->name('parentLogin');

    Route::get('get-classrank-classes',[StudentProfile::class,'get_classrank_classes'])->name('get-classrank-classes');
    Route::get('get-classrank-classes-data',[StudentProfile::class,'get_classrank_classes_data'])->name('get-classrank-classes-data');



Route::prefix('parent')->middleware(['auth:parent'])->group( function () {
    Route::get('get-mywork-parent-data',[ParentController::class,'get_mywork_parent_data'])->name('get-mywork-parent-data');
    Route::get('/u',             [ParentController::class, 'memberShowRedirect'])->name('parent.home');
    Route::get('/u/{name_id?}/', [ParentController::class, 'membershipShow'])->name('parent.dashboard');


    Route::get('/settings/account', [ParentSettingsController::class,'show'])->name('parent.settings');
    Route::post('/settings/account',[ParentSettingsController::class,'update'])->name('parent.update.settings');


    Route::get('logout' ,function (){
        auth('parent')->logout();
        return redirect()->route('getParentLogin');
    } )->name('parent.logout');
});




require __DIR__.'/auth.php';


