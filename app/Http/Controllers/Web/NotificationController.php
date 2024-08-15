<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){
        return view('notifications')->with([
            'title' => 'Notification',
            'notifications' => Auth::user()->notifications
        ]);
    }

    public function detail($notificationId){
        $notifications = Auth::user()->notifications;
        $notification = $notifications->where('id', $notificationId)->first();
        $notification->markAsRead();
        return redirect($notification->data['url']);
    }

    public function remove($notificationId){
        $notification = Auth::user()->notifications->where('id', $notificationId)->first();
        $notification->delete();
        return back()->with('success', 'Notification removed successfully');
    }
}