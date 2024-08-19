<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){
        return view('components.pages.notifications')->with([
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

    public function mark($notificationId){
        $notifications = Auth::user()->notifications;
        $notification = $notifications->where('id', $notificationId)->first();
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read');
    }

    public function remove($notificationId){
        $notification = Auth::user()->notifications->where('id', $notificationId)->first();
        $notification->delete();
        return back()->with('success', 'Notification removed successfully');
    }

    public function readAll(){
        Auth::user()->notifications->markAsRead();
        return back()->with('success', 'All notifications have been marked as read');
    }

    public function removeAll(){
        $notifications = Auth::user()->notifications;
        foreach ($notifications as $notification){
            $notification->delete();
        }
        return back()->with('success', 'All notifications have been removed');
    }
}