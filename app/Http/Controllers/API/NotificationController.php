<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request){
        try{
            $all_notification = $request->user()->notifications;
            $notifications = $all_notification->map(function($notification){
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'],
                    'message' => $notification->data['message'],
                    'status' => $notification->read_at == null ? 'unread' : 'read',
                    // 'url' => $notification->data['url'],
                ];
            });
            if($notifications->isNotEmpty()){
                return response()->json([
                    'notifications' => $notifications
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'No notifications available',
                ], 200);
            }
        } catch(\Exception $e){
            return response()->json([
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function mark($notificationId){
        try {
            Auth::user()->unreadNotifications->where('id', $notificationId)->markAsRead();
            return response()->json([
                'message' => 'Notification marked as read'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function remove($notificationId){
        try{
            $notification = Auth::user()->notifications->where('id', $notificationId)->first();
            $notification->delete();
            return response()->json([
                'message' => 'Notification removed'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}