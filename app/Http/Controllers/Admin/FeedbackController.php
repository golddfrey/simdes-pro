<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $feedbacks = Feedback::with('kepala')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function show($id)
    {
        $fb = Feedback::with('kepala')->findOrFail($id);
        return view('admin.feedback.show', compact('fb'));
    }
}
