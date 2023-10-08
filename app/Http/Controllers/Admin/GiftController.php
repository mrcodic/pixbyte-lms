<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GiftRequest;
use App\Http\Resources\Admin\GiftResource;
use App\Http\Resources\Admin\RedemtionResource;
use App\Models\Gift;
use App\Models\Redemption;
use App\Models\Setting;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class GiftController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:read-gifts|create-gifts|update-gifts|delete-gifts', ['only' => ['index', 'show', 'export'],]);
        $this->middleware('permission:create-gifts', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-gifts', ['only' => ['edit', 'update', 'parentUpdate']]);
        $this->middleware('permission:delete-gifts', ['only' => ['destroy']]);
    }


    public function index()
    {
        $setting = Setting::where('name', 'date_accept_redemptions')->first();
        return view('admin.gift.index', get_defined_vars());
    }

    public function redemptions(){
        return view('admin.gift.redemptions');

    }

    public function get_redemptions_data(Request $request)
    {
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $request['name'] = $request->search['value'];

        $gift = Redemption::orderBy('id','desc');
        $recordsFiltered = $gift->count();

        $gifts = RedemtionResource::collection($gift->skip($start)->take($limit)->get());

        return datatables($gifts)->setOffset($start)
            ->with([
                'recordsTotal'    => Redemption::count(),
                'recordsFiltered' => $recordsFiltered,
                'start' => $start
            ])->make(true);
    }

    public function get_gift_data(Request $request)
    {
        $start = request('start');
        $limit = ((request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10);

        $request['name'] = $request->search['value'];

        $gift = Gift::filter($request)->orderBy('id','desc');
        $recordsFiltered = $gift->count();

        $gifts = GiftResource::collection($gift->skip($start)->take($limit)->get());

        return datatables($gifts)->setOffset($start)
        ->with([
            'recordsTotal'    => Gift::count(),
            'recordsFiltered' => $recordsFiltered,
            'start' => $start
        ])->make(true);
    }


    public function create(){
        return view('admin.gift.create',get_defined_vars());
    }


    public function store(GiftRequest $request)
    {
        $gift = Gift::create([
            'name'  => $request->name,
            'price' => $request->price,
            'count' => $request->count,
            'status'=> $request->status ?? true,
        ]);

        if ($request->file('image')) {
            $file       = $request->file('image');
            $fileName   = date('YmdHi') . $file->getClientOriginalName();
            $img        = Image::make($file);

            if (!file_exists('uploads/gifts/'))
                mkdir('uploads/gifts/', 755, true);


            $img->resize(375,200, function ($constraint) {
                $constraint->aspectRatio();
            })->save('uploads/gifts/'.$fileName, 60);

            $gift->image = $fileName;
            $gift->save();
        }

        return response()->json(['status'=>true,'message'=>'Created Successfully'],200);
    }



    public function update(GiftRequest $request,gift $gift)
    {
        $gift->update([
            'name'  => $request->name,
            'price' => $request->price,
            'count' => $request->count,
            'status'=> $request->status ?? true,
        ]);

        if ($request->file('image')) {
            $file       = $request->file('image');
            $fileName   = date('YmdHi') . $file->getClientOriginalName();
            $img        = Image::make($file);

            if (!file_exists('uploads/gifts/'))
                mkdir('uploads/gifts/', 755, true);


            $img->resize(375,200, function ($constraint) {
                $constraint->aspectRatio();
            })->save('uploads/gifts/'.$fileName, 60);

            $gift->image = $fileName;
            $gift->save();
        }

        return response()->json(['status'=>true,'message'=>'Created Successfully'],200);
    }


    public function setting(Request $request)
    {

        $setting = Setting::where('name', 'date_accept_redemptions')->first();

        if ($setting) {

            $setting ? $setting->update([
                'value' => $request->date
            ]) :null;

            return response()->json(['status'=>true,'message'=>'update Successfully'],200);
        }

        return response()->json(['status'=>false,'message'=>'update failed'],500);

    }

    public function destroy(gift $gift)
    {
        $gift->users->count() > 0 ? $gift->delete() : $gift->forceDelete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }

    public function delete_selected(Request $request){
        gift::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }

}
