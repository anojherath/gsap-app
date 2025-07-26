<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserType;

class NotificationController extends Controller
{
    /**
     * Show notification form + sent list.
     */
    public function index()
    {
        // Fetch notifications for the logged-in user, latest 20
        $notifications = Notification::where('user_id', auth()->id())
                            ->latest()
                            ->take(20)
                            ->get();

        $userTypes = UserType::all();

        return view('admin.push_notification', compact('notifications', 'userTypes'));
    }

    /**
     * Send notification to users of selected user_type.
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_type_id' => 'nullable|exists:user_types,id'
        ]);

        $query = User::query();

        if ($request->user_type_id) {
            $query->where('user_type_id', $request->user_type_id);
        }

        $users = $query->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => $request->message,
            ]);
        }

        return redirect()->route('admin.push_notification')->with('success', 'Notification sent!');
    }

    /**
     * Mark notification as read.
     */
    public function markRead($id)
    {
        $notification = Notification::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }

        return back();
    }

    /**
     * Delete notification.
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

        $notification->delete();

        return back();
    }
}
