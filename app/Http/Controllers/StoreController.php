<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Setting;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request) {
        $giftCount   = Gift::where('count','>','0')->where('status',1)->count();
        $gifts=Gift::where('count','>','0')->where('status',1);
        if($request->ajax()){
            $gifts=$gifts->paginate(8);
            $html = '';
            $data='';
            $stored='';
            foreach ($gifts as $gift){
                 if(count($gift->favorites)>0){
                     $data=' <a href="#"  class="add_redemptions" id="add_redemptions-'.$gift->id.'" data-type="0" data-id="'.$gift->id.'">-</a>';
                 }else{
                     $data='<a href="#" id="add_redemptions-'.$gift->id.'" class="add_redemptions" data-type="1" data-id="'.$gift->id.'">+</a>';
                 }

                 if(count($gift->users)>0){
                     $stored='ACQUIRED';
                 }else{
                     $stored='ACQUIRE';
                 }

                $html.='
                 <div class="uk-width-1-4@m uk-width-1-1@s">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header uk-card-media-top">
                            <h3 class="total-points">'.$gift->price.' PT</h3>
                            <div class="gift-label">
                                <h3 class="uk-margin-remove">'.$gift->name.'</h3>
                            </div>
                        </div>
                        <div class="uk-card-body"  uk-grid>
                            <div class="acquire uk-width-2-3@s uk-text-center">
                                <a href="#"  class="add_store" id="add_store-'.$gift->id.'" data-id="'.$gift->id.'" >'.$stored.'</a>
                            </div>
                            <div class="love uk-width-1-3@s" id="index-'.$gift->id.'">
                              '.$data.'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
            return $html;
        }else{
        $gifts=$gifts->paginate(8);
        }
        return view('store.index',get_defined_vars());
    }
    public function add_redemptions(Request $request) {
        $gift=Gift::find($request->gift_id);
        $gift->favorites()->toggle($request->student_id);
        return response()->json(['status'=>true,'data'=>['type'=>$request->type,'id'=>$gift->id]]);
    }

    public function add_store(Request $request) {
        $gift=Gift::find($request->gift_id);
         $user=User::find($request->student_id);
        //  $settingDateRange=Setting::where('name','date_accept_redemptions')->where('type','store')->first();;
        // $dateParts = explode(" to ", $settingDateRange->value);
        // $startDate = new DateTime($dateParts[0]);
        // $endDate = new DateTime($dateParts[1]);
        // $now=new DateTime();
        // if ($now >= $startDate && $now <= $endDate) {
            if((int)$gift->price <=(int) $user->student->exp){
                $gift->users()->sync($request->student_id);
                $gift->update(['count'=>(int)$gift->count - 1]);
                $user->student->update(['exp'=>(int)$user->student->exp - (int)$gift->price]);
                return response()->json(['status'=>true,'data'=>['id'=>$gift->id]]);
            }else{
                return response()->json(['status'=>false,'message'=>'not enough point to buy'],200);
            }
        // }else{
        //     return response()->json(['status'=>false,'message'=>"This not accurate  date to acquired come back later"],200);
        // }


    }
    public function my_fav_store(Request $request) {
        $giftCount   = Gift::whereHas('favorites',function ($q){
            $q->where('user_id',auth()->id());
        })->count();
        $gifts=Gift::whereHas('favorites',function ($q){
            $q->where('user_id',auth()->id());
        });
        if($request->ajax()){
            $gifts=$gifts->paginate(8);
            $html = '';
            $data='';
            $stored='';
            foreach ($gifts as $gift){
                if(count($gift->favorites)>0){
                    $data=' <a href="#"  class="add_redemptions" id="add_redemptions-'.$gift->id.'" data-type="0" data-id="'.$gift->id.'">-</a>';
                }else{
                    $data='<a href="#" id="add_redemptions-'.$gift->id.'" class="add_redemptions" data-type="1" data-id="'.$gift->id.'">+</a>';
                }

                if(count($gift->users)>0){
                    $stored='ACQUIRED';
                }else{
                    $stored='ACQUIRE';
                }

                $html.='
                 <div class="uk-width-1-4@m uk-width-1-1@s">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header uk-card-media-top">
                            <h3 class="total-points">'.$gift->price.' PT</h3>
                            <div class="gift-label">
                                <h3 class="uk-margin-remove">'.$gift->name.'</h3>
                            </div>
                        </div>
                        <div class="uk-card-body"  uk-grid>
                            <div class="acquire uk-width-2-3@s uk-text-center">
                                <a href="#"  class="add_store" id="add_store-'.$gift->id.'" data-id="'.$gift->id.'" >'.$stored.'</a>
                            </div>
                            <div class="love uk-width-1-3@s" id="index-'.$gift->id.'">
                              '.$data.'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
            return $html;
        }else{
            $gifts=$gifts->paginate(8);
        }
        return view('store.myFavorite',get_defined_vars());
    }
    public function my_redemptions_store(Request $request) {
        $giftCount   = Gift::whereHas('favorites',function ($q){
            $q->where('user_id',auth()->id());
        })->count();
        $gifts=Gift::whereHas('users',function ($q){
            $q->where('user_id',auth()->id());
        });

        if($request->ajax()){
            $gifts=$gifts->paginate(8);
            $html = '';
            $data='';
            $stored='';
            foreach ($gifts as $gift){
                if(count($gift->favorites)>0){
                    $data=' <a href="#"  class="add_redemptions" id="add_redemptions-'.$gift->id.'" data-type="0" data-id="'.$gift->id.'">-</a>';
                }else{
                    $data='<a href="#" id="add_redemptions-'.$gift->id.'" class="add_redemptions" data-type="1" data-id="'.$gift->id.'">+</a>';
                }

                if(count($gift->users)>0){
                    $stored='ACQUIRED';
                }else{
                    $stored='ACQUIRE';
                }

                $html.='
                 <div class="uk-width-1-4@m uk-width-1-1@s">
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header uk-card-media-top">
                            <h3 class="total-points">'.$gift->price.' PT</h3>
                            <div class="gift-label">
                                <h3 class="uk-margin-remove">'.$gift->name.'</h3>
                            </div>
                        </div>
                        <div class="uk-card-body"  uk-grid>
                            <div class="acquire uk-width-2-3@s uk-text-center">
                                <a href="#"  class="add_store" id="add_store-'.$gift->id.'" data-id="'.$gift->id.'" >'.$stored.'</a>
                            </div>
                            <div class="love uk-width-1-3@s" id="index-'.$gift->id.'">
                              '.$data.'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
            return $html;
        }else{
            $gifts=$gifts->paginate(8);
        }
        return view('store.myredemptions',get_defined_vars());
    }

}
