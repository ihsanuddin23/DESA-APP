<?php

namespace App\Notifications;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PendudukDitambahkan extends Notification
{
    use Queueable;

    public function __construct(
        public Penduduk $penduduk,
        public User $addedBy
    ) {}

    /**
     * Channel notifikasi — pakai database saja (tidak ada email/broadcast).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke DB.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'penduduk_ditambahkan',
            'title'        => 'Data Penduduk Baru',
            'message'      => "{$this->addedBy->name} ({$this->addedBy->role_label}) menambahkan warga: {$this->penduduk->nama}",
            'penduduk_id'  => $this->penduduk->id,
            'penduduk_nama'=> $this->penduduk->nama,
            'penduduk_nik' => $this->penduduk->nik,
            'penduduk_rt'  => $this->penduduk->rt,
            'penduduk_rw'  => $this->penduduk->rw,
            'added_by_id'  => $this->addedBy->id,
            'added_by_name'=> $this->addedBy->name,
            'added_by_role'=> $this->addedBy->role,
            'icon'         => 'bi-person-plus-fill',
            'color'        => 'success',
            'url'          => route('admin.penduduk.show', $this->penduduk->id),
        ];
    }
}
