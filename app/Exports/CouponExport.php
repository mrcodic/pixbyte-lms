<?php

namespace App\Exports;

use App\Models\Coupon;
use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CouponExport implements FromCollection ,WithHeadings
{
    protected $request;
    public function __construct($id)
    {
        $this->request=$id;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(is_array($this->request['selected_ids'])){
            $ids=$this->request['selected_ids'];
        }else{
            $ids=explode(',',$this->request['selected_ids']);
        }
        if($this->request['selected_ids'] &&$this->request['selected_ids']!=null ){
            $coupons =  Coupon::whereIn('id',$ids)->select('id','code')->get();
        }else{
            $coupons =  Coupon::select('id','code')->get();
        }

        $data = $coupons->transform(function ($q){

            return[
                'id'=>"# (".$q->id.")",
                'code'=>$q->code,
            ];
        });
        return $data;
    }
    public function headings(): array
    {
        return ['Serial Number (ID)','Coupon'];
    }
}
