<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewAnggotaChangeRequest extends Notification
{
    use Queueable;

    public $requestModel;

    public function __construct($requestModel)
    {
        $this->requestModel = $requestModel;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $req = $this->requestModel;
        return [
            'title' => 'Permintaan perubahan anggota keluarga',
            'body' => 'Ada permintaan baru ('.($req->action ?? 'n/a').') dari kepala keluarga #'.$req->kepala_keluarga_id,
            'request_id' => $req->id,
            'kepala_keluarga_id' => $req->kepala_keluarga_id,
            'url' => route('admin.anggota.requests.show', $req->id),
        ];
    }
}
