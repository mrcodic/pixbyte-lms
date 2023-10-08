<?php

namespace App\Http\Controllers\Api\Student;

use App\Enums\Http;
use App\Helpers\MessageResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function get_notifications(Request $request){
        $notifications =auth()->user();

        if($request->has('unread') && $request->unread){
            $notifications=$notifications->unreadNotifications();
           }else{
            $notifications=$notifications->notifications();
           }
           $notifications= NotificationResource::collection($notifications->paginate($request->per_page??10));
           return new MessageResponse(
            message: 'Get Notification Data',
            code: Http::OK,
            body: [
                'notifications' => $notifications,
            ]
        );
       }
}
