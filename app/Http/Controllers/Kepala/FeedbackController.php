<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KepalaKeluarga;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function create(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        if (! $kepalaId) return redirect()->route('kepala.login');
        return view('kepala.feedback.create');
    }

    public function store(Request $request)
    {
        $kepalaId = $request->session()->get('kepala_keluarga_id');
        if (! $kepalaId) return redirect()->route('kepala.login');

        $data = $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        Feedback::create([
            'kepala_keluarga_id' => $kepalaId,
            'message' => $data['message'],
        ]);

        return redirect()->route('kepala.dashboard')->with('status', 'Terima kasih, kritik/saran Anda telah dikirim.');
    }
}
