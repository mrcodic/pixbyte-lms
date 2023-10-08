<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllRead(Request $request){
        $notification = auth()->user()->notifications;
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['status'=>true],200);
    }
    public function loadmore_notification(Request $request){

        $notification = auth()->user()->unreadNotifications()->paginate(10,['*'],'page',$request->page);
        $notification= NotificationResource::collection($notification);

        return response()->json(['status'=>true,'data'=>$notification],200);
    }
}
