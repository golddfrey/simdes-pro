<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user) return redirect()->route('admin.login');

        $perPage = (int) $request->input('per_page', 15);
        $filter = $request->input('filter', 'all'); // all|unread|read
        $q = $request->input('q', '');

        $query = $user->notifications()->orderBy('created_at', 'desc');

        if ($filter === 'unread') {
            $query = $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query = $query->whereNotNull('read_at');
        }

        if (! empty($q)) {
            $query = $query->where('data', 'like', "%{$q}%");
        }

        $notifications = $query->paginate($perPage)->withQueryString();

        return view('notifications.index', [
            'notifications' => $notifications,
            'role' => 'admin',
            'filter' => $filter,
            'q' => $q,
        ]);
    }

    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        if (! $user) return redirect()->route('admin.login');

        $user->unreadNotifications->markAsRead();

        return back()->with('status', 'Semua notifikasi telah ditandai terbaca.');
    }
}
