<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AnggotaRequestProcessed extends Notification
{
    use Queueable;

    public $requestModel;
    public $status; // 'approved' or 'rejected'

    public function __construct($requestModel, $status)
    {
        $this->requestModel = $requestModel;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $req = $this->requestModel;
        $statusLabel = $this->status === 'approved' ? 'disetujui' : 'ditolak';

        return [
            'title' => 'Status permintaan anggota keluarga',
            'body' => "Permintaan #{$req->id} telah {$statusLabel} oleh admin.",
            'request_id' => $req->id,
            'status' => $this->status,
            'kepala_keluarga_id' => $req->kepala_keluarga_id,
            // direct kepala ke halaman anggota mereka
            'url' => route('kepala.anggota.index'),
        ];
    }
}
