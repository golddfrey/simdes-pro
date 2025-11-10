<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\KepalaKeluargaController;
use App\Http\Controllers\Admin\AnggotaChangeRequestController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Kepala\NotificationController as KepalaNotificationController;
use App\Http\Controllers\Kepala\AnggotaKeluargaController as KepalaAnggotaController;
use App\Http\Controllers\Kepala\SuratRequestController;
use App\Http\Controllers\KepalaAuthController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function (\Illuminate\Http\Request $request) {
	// jika admin login (Laravel Auth), langsung ke admin dashboard
	if (Auth::check()) {
		return redirect()->route('admin.dashboard');
	}
	// jika kepala login (session), langsung ke kepala dashboard
	if ($request->session()->has('kepala_keluarga_id')) {
		return redirect()->route('kepala.dashboard');
	}
	return app()->make(App\Http\Controllers\HomeController::class)->index();
})->name('home');

// Provide a generic named 'login' route so Laravel's auth middleware can redirect unauthenticated
// users to a valid route. The admin login form is used here. We redirect logged-in users to their dashboards.
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
Route::get('login', function (\Illuminate\Http\Request $request) {
	if (Auth::check()) return redirect()->route('admin.dashboard');
	if ($request->session()->has('kepala_keluarga_id')) return redirect()->route('kepala.dashboard');
	return app()->make(AdminAuthController::class)->showLogin();
})->name('login');
Route::post('login', function (\Illuminate\Http\Request $request) {
	if (Auth::check()) return redirect()->route('admin.dashboard');
	if ($request->session()->has('kepala_keluarga_id')) return redirect()->route('kepala.dashboard');
	return app()->make(AdminAuthController::class)->login($request);
})->name('login.post');

// Logout route: use POST and invalidate session, then redirect to home
Route::post('/logout', function (Request $request) {
	Auth::logout();
	$request->session()->invalidate();
	$request->session()->regenerateToken();
	return redirect()->route('home');
})->name('logout');

// Admin auth (login/logout)
Route::get('admin/login', function (\Illuminate\Http\Request $request) {
	if (Auth::check()) return redirect()->route('admin.dashboard');
	if ($request->session()->has('kepala_keluarga_id')) return redirect()->route('kepala.dashboard');
	return app()->make(AuthController::class)->showLogin();
})->name('admin.login');
Route::post('admin/login', function (\Illuminate\Http\Request $request) {
	if (Auth::check()) return redirect()->route('admin.dashboard');
	if ($request->session()->has('kepala_keluarga_id')) return redirect()->route('kepala.dashboard');
	return app()->make(AuthController::class)->login($request);
})->name('admin.login.post');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin routes to manage kepala keluarga (requires admin auth + is_admin)
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
	Route::get('kepala', [KepalaKeluargaController::class, 'index'])->name('kepala.index');
		Route::get('kepala/{id}/anggota', [KepalaKeluargaController::class, 'anggota'])->name('kepala.anggota');
	Route::get('kepala/create', [KepalaKeluargaController::class, 'create'])->name('kepala.create');
	Route::post('kepala', [KepalaKeluargaController::class, 'store'])->name('kepala.store');
	// Admin review of anggota change requests
	Route::get('anggota/requests', [AnggotaChangeRequestController::class, 'index'])->name('anggota.requests.index');
	Route::get('anggota/requests/{id}', [AnggotaChangeRequestController::class, 'show'])->name('anggota.requests.show');
	Route::post('anggota/requests/{id}/approve', [AnggotaChangeRequestController::class, 'approve'])->name('anggota.requests.approve');
	Route::post('anggota/requests/{id}/reject', [AnggotaChangeRequestController::class, 'reject'])->name('anggota.requests.reject');
	// admin dashboard
	Route::get('dashboard', function () {
		$jumlahKepala = \App\Models\KepalaKeluarga::count();
		$jumlahAnggota = \App\Models\AnggotaKeluarga::count();
		$jumlahPenduduk = $jumlahKepala + $jumlahAnggota;

		$pendingPengajuan = \App\Models\AnggotaKeluargaChangeRequest::where('status', 'pending')->count();

		$since = \Illuminate\Support\Carbon::now()->subDays(7);
		$recentKepala = \App\Models\KepalaKeluarga::where('created_at', '>=', $since)->orderBy('created_at', 'desc')->get();
		$recentAnggota = \App\Models\AnggotaKeluarga::where('created_at', '>=', $since)->orderBy('created_at', 'desc')->get();

		// Age distribution (combine kepala + anggota)
		$birthDates = [];
		foreach (\App\Models\KepalaKeluarga::select('tanggal_lahir')->whereNotNull('tanggal_lahir')->get() as $k) {
			$birthDates[] = $k->tanggal_lahir->format('Y-m-d');
		}
		foreach (\App\Models\AnggotaKeluarga::select('tanggal_lahir')->whereNotNull('tanggal_lahir')->get() as $a) {
			$birthDates[] = $a->tanggal_lahir->format('Y-m-d');
		}

		$ageBuckets = ['0-9'=>0,'10-19'=>0,'20-29'=>0,'30-39'=>0,'40-49'=>0,'50-59'=>0,'60-69'=>0,'70+'=>0];
		foreach ($birthDates as $bd) {
			$age = \Illuminate\Support\Carbon::parse($bd)->age;
			if ($age < 10) $ageBuckets['0-9']++;
			elseif ($age < 20) $ageBuckets['10-19']++;
			elseif ($age < 30) $ageBuckets['20-29']++;
			elseif ($age < 40) $ageBuckets['30-39']++;
			elseif ($age < 50) $ageBuckets['40-49']++;
			elseif ($age < 60) $ageBuckets['50-59']++;
			elseif ($age < 70) $ageBuckets['60-69']++;
			else $ageBuckets['70+']++;
		}

		// Gender distribution
		$genders = ['L' => 0, 'P' => 0, 'other' => 0];
		foreach (\App\Models\KepalaKeluarga::select('jenis_kelamin')->get() as $k) {
			$g = $k->jenis_kelamin ?? 'other';
			if (!isset($genders[$g])) $genders['other']++;
			else $genders[$g]++;
		}
		foreach (\App\Models\AnggotaKeluarga::select('jenis_kelamin')->get() as $a) {
			$g = $a->jenis_kelamin ?? 'other';
			if (!isset($genders[$g])) $genders['other']++;
			else $genders[$g]++;
		}

		return view('admin.dashboard', compact(
			'jumlahKepala', 'jumlahPenduduk', 'pendingPengajuan', 'recentKepala', 'recentAnggota', 'ageBuckets', 'genders', 'jumlahAnggota'
		));
	})->name('dashboard');

	// penduduk
	Route::get('penduduk', [\App\Http\Controllers\Admin\PendudukController::class, 'index'])->name('penduduk.index');
    	// autocomplete/search endpoint for penduduk (used by admin UI)
    	Route::get('penduduk/search', [\App\Http\Controllers\Admin\PendudukController::class, 'search'])->name('penduduk.search');

		// feedback dari kepala
		Route::get('feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
		Route::get('feedback/{id}', [\App\Http\Controllers\Admin\FeedbackController::class, 'show'])->name('feedback.show');

	// mark notification as read and redirect (admin)
	Route::get('notifications/{id}/go', function ($id) {
		$user = Auth::user();
		if (! $user) return redirect()->route('admin.dashboard');
		$notif = $user->notifications()->where('id', $id)->first();
		if ($notif) {
			$data = $notif->data;
			$notif->markAsRead();
			return redirect($data['url'] ?? route('admin.dashboard'));
		}
		return redirect()->route('admin.dashboard');
	})->name('notifications.go');

	// daftar notifikasi (admin): search/filter/paginate
	Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');
});

// Kepala keluarga auth routes (simple session-based auth by NIK)
Route::get('kepala/login', function (\Illuminate\Http\Request $request) {
	// jika kepala sudah login via session, redirect ke dashboard kepala
	if ($request->session()->has('kepala_keluarga_id')) return redirect()->route('kepala.dashboard');
	// jika admin sudah login, jangan izinkan akses ke login kepala
	if (Auth::check()) return redirect()->route('admin.dashboard');
	return app()->make(KepalaAuthController::class)->showLogin();
})->name('kepala.login');

Route::post('kepala/login', function (\Illuminate\Http\Request $request) {
	if ($request->session()->has('kepala_keluarga_id')) return redirect()->route('kepala.dashboard');
	if (Auth::check()) return redirect()->route('admin.dashboard');
	return app()->make(KepalaAuthController::class)->login($request);
})->name('kepala.login.post');

Route::get('kepala/dashboard', [KepalaAuthController::class, 'dashboard'])->name('kepala.dashboard');
// Cetak Kartu Keluarga (print view)
Route::get('kepala/kk/print', [KepalaAuthController::class, 'printKK'])->name('kepala.kk.print');
Route::post('kepala/logout', [KepalaAuthController::class, 'logout'])->name('kepala.logout');

// Kepala keluarga routes for anggota management and surat requests
Route::prefix('kepala')->name('kepala.')->group(function () {
	Route::get('anggota', [KepalaAnggotaController::class, 'index'])->name('anggota.index');
	Route::get('anggota/create', [KepalaAnggotaController::class, 'create'])->name('anggota.create');
	Route::post('anggota', [KepalaAnggotaController::class, 'store'])->name('anggota.store');
	Route::get('anggota/{id}/edit', [KepalaAnggotaController::class, 'edit'])->name('anggota.edit');
	Route::post('anggota/{id}', [KepalaAnggotaController::class, 'update'])->name('anggota.update');
	Route::post('anggota/{id}/report-death', [KepalaAnggotaController::class, 'reportDeath'])->name('anggota.report_death');

	// surat
	Route::get('surat/create', [SuratRequestController::class, 'create'])->name('surat.create');
	Route::post('surat', [SuratRequestController::class, 'store'])->name('surat.store');

	// kritik dan saran
	Route::get('feedback/create', [\App\Http\Controllers\Kepala\FeedbackController::class, 'create'])->name('feedback.create');
	Route::post('feedback', [\App\Http\Controllers\Kepala\FeedbackController::class, 'store'])->name('feedback.store');

	// mark notification as read and redirect (kepala)
	Route::get('notifications/{id}/go', function (\Illuminate\Http\Request $request, $id) {
		$kepalaId = $request->session()->get('kepala_keluarga_id');
		if (! $kepalaId) return redirect()->route('kepala.login');
		$kepala = \App\Models\KepalaKeluarga::find($kepalaId);
		if (! $kepala) return redirect()->route('kepala.login');
		$notif = $kepala->notifications()->where('id', $id)->first();
		if ($notif) {
			$data = $notif->data;
			$notif->markAsRead();
			return redirect($data['url'] ?? route('kepala.dashboard'));
		}
		return redirect()->route('kepala.dashboard');
	})->name('notifications.go');

	// daftar notifikasi (kepala)
	Route::get('notifications', [KepalaNotificationController::class, 'index'])->name('notifications.index');
	Route::post('notifications/mark-all-read', [KepalaNotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');
});
