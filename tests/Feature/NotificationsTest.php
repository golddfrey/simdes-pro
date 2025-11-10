<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAnggotaChangeRequest;
use App\Notifications\AnggotaRequestProcessed;
use Illuminate\Notifications\Notifiable as NotifiableTrait;

it('mengirim notifikasi ke admin ketika kepala membuat change request', function () {
    Notification::fake();

    // buat objek admin ringan (tidak perlu persist ke DB ketika Notification::fake digunakan)
    $admin = new class { use NotifiableTrait; public $id = 1; public function getKey() { return $this->id; } };

    // buat objek change request ringan
    $cr = (object) [
        'id' => 123,
        'kepala_keluarga_id' => 999,
        'action' => 'add',
        'payload' => [],
    ];

    Notification::send([$admin], new NewAnggotaChangeRequest($cr));

    Notification::assertSentTo(
        [$admin],
        NewAnggotaChangeRequest::class,
        function ($notification, $channels) use ($cr, $admin) {
            $data = $notification->toDatabase($admin);
            return isset($data['request_id']) && $data['request_id'] === $cr->id;
        }
    );
});

it('mengirim notifikasi hasil pemrosesan ke kepala setelah admin memproses', function () {
    Notification::fake();

    // buat objek kepala ringan
    $kepala = new class { use NotifiableTrait; public $id = 42; public function getKey() { return $this->id; } };

    $cr = (object) [
        'id' => 555,
        'kepala_keluarga_id' => 42,
        'action' => 'add',
        'payload' => ['nama' => 'Test2'],
    ];

    $kepala->notify(new AnggotaRequestProcessed($cr, 'approved'));

    Notification::assertSentTo(
        [$kepala],
        AnggotaRequestProcessed::class,
        function ($notification, $channels) use ($cr, $kepala) {
            $data = $notification->toDatabase($kepala);
            return isset($data['request_id']) && $data['request_id'] === $cr->id && ($data['status'] ?? null) === 'approved';
        }
    );
});
