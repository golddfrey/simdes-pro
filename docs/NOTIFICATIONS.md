# Dokumentasi Notifikasi — SimDes

Semua petunjuk, verifikasi, dan catatan untuk fitur notifikasi (database channel) di aplikasi SimDes.

Semua instruksi ditulis dalam Bahasa Indonesia.

## Ringkasan fitur

-   Notifikasi dibuat ketika Kepala Keluarga mengajukan permintaan perubahan/penambahan anggota keluarga.
-   Notifikasi dikirim ke semua user admin (database notifications).
-   Ketika admin memproses (approve/reject), notifikasi hasil dikirim ke Kepala Keluarga.
-   Ada dropdown bell pada topbar yang menampilkan unread (badge) dan preview 10 notifikasi terakhir.
-   Halaman lengkap tersedia untuk manajemen notifikasi:
    -   Admin: `/admin/notifications` (route name `admin.notifications.index`)
    -   Kepala: `/kepala/notifications` (route name `kepala.notifications.index`)

## File terkait (perubahan yang dibuat)

-   Migration: `database/migrations/2025_11_10_000005_create_notifications_table.php`
-   Notifikasi classes: `app/Notifications/NewAnggotaChangeRequest.php`, `app/Notifications/AnggotaRequestProcessed.php` (sudah ada)
-   Topbar dropdown: `resources/views/layouts/windmill.blade.php` (ditambahkan dropdown, badge, link "Lihat semua")
-   Halaman daftar notifikasi: `resources/views/notifications/index.blade.php`
-   Controller admin: `app/Http/Controllers/Admin/NotificationController.php`
-   Controller kepala: `app/Http/Controllers/Kepala/NotificationController.php`
-   Routes: `routes/web.php` (ditambahkan route index & mark-all-read untuk admin & kepala)
-   Tests: `tests/Feature/NotificationsTest.php` (Pest tests untuk alur notifikasi)

## Migrasi dan instalasi

1. Pastikan konfigurasi database di `.env` sudah benar.
2. Jalankan migration (hanya migration notifikasi baru jika Anda tidak ingin menjalankan seluruh migrasi):

```pwsh
php artisan migrate --path=database/migrations/2025_11_10_000005_create_notifications_table.php --force
```

3. Jika Anda ingin menjalankan seluruh migrasi terhadap lingkungan development:

```pwsh
php artisan migrate --force
```

Catatan: pada beberapa environment (mis. repository ini), beberapa migration lain mungkin sudah dibuat; jika migrasi penuh menyebabkan error "table already exists", jalankan migration per-file atau reset DB sesuai kebutuhan.

## Cara memverifikasi (manual)

1. Simulasi pengiriman notifikasi (admin):

```pwsh
php artisan tinker --execute "$u=\App\Models\User::where('is_admin',true)->first(); if(! $u) { $u=\App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('secret'), 'is_admin'=>true]); } $cr = \App\Models\AnggotaKeluargaChangeRequest::create(['kepala_keluarga_id'=>1,'action'=>'add','payload'=>[], 'status'=>'pending']); \Illuminate\Support\Facades\Notification::send(\App\Models\User::where('is_admin',true)->get(), new \App\Notifications\NewAnggotaChangeRequest($cr)); echo 'DONE';"
```

2. Buka dashboard admin, klik ikon bell. Anda akan melihat badge unread dan preview notifikasi.
3. Klik "Lihat semua" untuk membuka `/admin/notifications` — di sana Anda dapat mencari, memfilter (all/unread/read), dan menandai semua sebagai dibaca.
4. Untuk menguji alur admin → kepala, Anda dapat memproses request di UI admin (approve/reject) atau membuat notifikasi `AnggotaRequestProcessed` via tinker dan memeriksa halaman notifikasi kepala.

## Cara menjalankan test otomatis (Pest)

Tests yang saya tambahkan hanya memverifikasi logika pengiriman notifikasi (bukan migrasi penuh). Jalankan:

```pwsh
php artisan test --filter=NotificationsTest -v
```

Hasil di lingkungan saya: 2 tests passed.

## Edge cases & catatan implementasi

-   Penyimpanan notifikasi menggunakan channel `database`. Pastikan migrasi tabel `notifications` dijalankan.
-   Jika tidak ada admin (user dengan `is_admin = true`), Notification::send ke koleksi kosong tidak menghasilkan error, namun tidak ada notifikasi yang disimpan — disarankan minimal ada satu admin.
-   Pencarian pada daftar notifikasi menggunakan `where('data', 'like', '%...%')` — ini adalah pencarian sederhana terhadap JSON serial yang ada di kolom `data`. Untuk pencarian lebih presisi, pertimbangkan mengekstrak field penting (seperti `request_id`, `title`) ke kolom tersendiri ketika pembentukan notifikasi.
-   Untuk skalabilitas: gunakan queued notifications (set `QUEUE_CONNECTION` ke `redis` atau `database` dan jalankan worker `php artisan queue:work`) sehingga pengiriman notifikasi tidak memblokir request user.
-   Untuk UX real-time: implementasikan broadcasting (Laravel Echo + Pusher/Redis) sehingga admin/kepala menerima notifikasi tanpa refresh.

## Keamanan

-   Route admin sudah berada di group `auth` + middleware `IsAdmin` (pastikan middleware ada dan benar).
-   Route kepala bergantung pada session `kepala_keluarga_id` (simple session-based login). Pastikan session handling aman.

## Rekomendasi perbaikan lanjutan

1. Tambah endpoint AJAX untuk "Tandai semua sebagai dibaca" agar tidak reload halaman. (mudah ditambahkan di controller + route)
2. Tambahkan tombol "Tandai sebagai terbaca/hapus" per-notifikasi di daftar untuk manajemen lebih granular.
3. Implementasikan broadcasting agar notifikasi muncul real-time.
4. Normalisasi field yang sering dicari (title, request_id) ke kolom terpisah agar pencarian lebih cepat.
5. Tambahkan test integrasi HTTP yang menjalankan alur penuh (create request via controller, check DB, process via admin controller).

Jika Anda mau, saya bisa segera menerapkan (1) dan (2) sebagai perbaikan UX berikutnya.

---

Dokumentasi ini dibuat otomatis oleh pengubah kode; jika ada yang kurang jelas atau Anda ingin format yang lain (mis. wiki, README utama), beri tahu saya.
