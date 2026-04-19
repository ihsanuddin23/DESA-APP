<?php

namespace App\Notifications;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PendudukDiperbarui extends Notification
{
    use Queueable;

    public function __construct(
        public Penduduk $penduduk,
        public User $updatedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'penduduk_diperbarui',
            'title'        => 'Data Penduduk Diperbarui',
            'message'      => "{$this->updatedBy->name} ({$this->updatedBy->role_label}) memperbarui data: {$this->penduduk->nama}",
            'penduduk_id'  => $this->penduduk->id,
            'penduduk_nama'=> $this->penduduk->nama,
            'penduduk_nik' => $this->penduduk->nik,
            'penduduk_rt'  => $this->penduduk->rt,
            'penduduk_rw'  => $this->penduduk->rw,
            'updated_by_id'=> $this->updatedBy->id,
            'updated_by_name' => $this->updatedBy->name,
            'updated_by_role' => $this->updatedBy->role,
            'icon'         => 'bi-pencil-square',
            'color'        => 'info',
            'url'          => route('admin.penduduk.show', $this->penduduk->id),
        ];
    }
}
