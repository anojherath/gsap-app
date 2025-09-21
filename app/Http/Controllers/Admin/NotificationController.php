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
     * Supports optional filtering by user type.
     */
    public function index(Request $request)
    {
        $userTypes = UserType::all();

        // Fetch latest notifications with user and user type
        $query = Notification::with('user.userType')->latest();

        // Apply filter by user_type_id if selected
        if ($request->filled('user_type_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('user_type_id', $request->user_type_id);
            });
        }

        $notifications = $query->take(50)->get(); // adjust limit if needed

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

        return redirect()->route('admin.push_notification')
                         ->with('success', 'Notification sent!');
    }

    /**
     * Mark notification as read for current user.
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
     * Disable deleting notifications to preserve for all users.
     */
    public function destroy($id)
    {
        return back()->with('error', 'Deleting notifications is disabled to preserve them for all users.');
    }
}
