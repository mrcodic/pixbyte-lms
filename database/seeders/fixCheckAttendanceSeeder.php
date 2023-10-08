<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\CouponUsed;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class fixCheckAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendance=Attendance::where('classroom_id',28)->get();
        foreach ($attendance as $att) {
             $couponUsed=CouponUsed::where('classroom_id',$att->classroom_id)
             ->where('coupon_used_id',$att->attendance_id)
             ->where('user_id',$att->student_id);
             if($couponUsed->exists()){
                 $att->update(['status'=>1]);
             }else{
                 $att->update(['status'=>Null]);
             }
        }
    }
}
