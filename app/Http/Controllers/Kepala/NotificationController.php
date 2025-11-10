<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        if (! $kepalaId) return redirect()->route('kepala.login');

        $kepala = \App\Models\KepalaKeluarga::find($kepalaId);
        if (! $kepala) return redirect()->route('kepala.login');

        $perPage = (int) $request->input('per_page', 15);
        $filter = $request->input('filter', 'all');
        $q = $request->input('q', '');

        $query = $kepala->notifications()->orderBy('created_at', 'desc');

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
            'role' => 'kepala',
            'filter' => $filter,
            'q' => $q,
        ]);
    }

    public function markAllRead(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        if (! $kepalaId) return redirect()->route('kepala.login');

        $kepala = \App\Models\KepalaKeluarga::find($kepalaId);
        if (! $kepala) return redirect()->route('kepala.login');

        $kepala->unreadNotifications->markAsRead();

        return back()->with('status', 'Semua notifikasi telah ditandai terbaca.');
    }
}
