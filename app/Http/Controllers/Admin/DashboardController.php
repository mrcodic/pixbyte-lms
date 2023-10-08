<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CouponUsedResource;
use App\Models\Coupon;
use App\Models\CouponUsed;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getCoupons(){
        //commit
        $grades     =   Grade::all();
        $coupons    =   Coupon::count();
        $TotalPrice =   Coupon::sum('price');
        $couponsUsedArray=   CouponUsed::pluck('coupon_id')->toArray();
        $couponsused=   Coupon::whereIn('id',$couponsUsedArray)->count();
        // $couponsused=   Coupon::has('couponUsed')->toSql();

        $couponsusedTotalPrice      =   Coupon::whereIn('id',$couponsUsedArray)->sum('price');
        $couponsusedThisMonthcCount =   Coupon::whereIn('id',$couponsUsedArray)->whereBetween('created_at', [Carbon::now()->subDays(33),Carbon::now()])->count();
        $couponsusedThisMonthcprice =   Coupon::whereIn('id',$couponsUsedArray)->whereBetween('created_at', [Carbon::now()->subDays(33),Carbon::now()])->sum('price');

        $couponsNotused = Coupon::whereNotIn('id',$couponsUsedArray)->count();
        $chartDataByDay = $this->getChartData(Carbon::now()->subDays(30),Carbon::now());
        $chartDataByDayDate = json_encode(array_keys($chartDataByDay));
        $chartDataByDayCount= json_encode(array_values($chartDataByDay));

       return view('admin.dashboard.dashboard-coupons',get_defined_vars());
    }


    public function fitterChart(Request $request){
        $chartDataByDay = $this->getChartData(Carbon::make($request->start_date),Carbon::make($request->end_date));
        $chartDataByDayDate = json_encode(array_keys($chartDataByDay));
        $chartDataByDayCount= json_encode(array_values($chartDataByDay));
        return response()->json(['status'=>true,'date'=>$chartDataByDayDate,'count'=>$chartDataByDayCount],200);

    }

    private function getChartData($startDate,$endDate){
        $chartData = CouponUsed::select([
                DB::raw('DATE(created_at) AS date'),
                DB::raw('COUNT(id) AS count'),
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->toArray();

        $chartDataByDay = [];
        $date = $endDate;
        $days=$endDate->diffInDays($startDate);
        // dd($days);
        for ($i = 0; $i < $days; $i++) {
            $dateString = $date->format('d M');
            if (!isset($chartDataByDay[$dateString])) {
                $chartDataByDay[$dateString] = 0;
            }
            $date->subDay();
        }

        foreach ($chartData as $data) {
            $date=Carbon::make($data['date'])->format('d M');
            $chartDataByDay[$date] = $data['count'];
        }

        return $chartDataByDay;

    }


    public function get_students_coupons(Request $request)
    {
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $request['name'] = $request->search['value'];
        $students = Coupon::filter($request)->orderBy('created_at','desc');
        $recordsFiltered = $students->count();
        $students = CouponUsedResource::collection($students->skip($start)->take($limit)->get());

        return datatables($students)->setOffset($start)
        ->with([
            'recordsTotal'    => Coupon::count(),
            'recordsFiltered' => $recordsFiltered,
            'start' => $start
        ])->make(true);
    }


}
