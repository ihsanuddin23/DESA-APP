<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengaduanBaru extends Notification
{
    use Queueable;

    public function __construct(public Pengaduan $pengaduan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'pengaduan_baru',
            'title'        => 'Pengaduan Baru',
            'message'      => "{$this->pengaduan->nama_pengadu} mengirim aduan: {$this->pengaduan->judul}",
            'pengaduan_id' => $this->pengaduan->id,
            'kode_tiket'   => $this->pengaduan->kode_tiket,
            'kategori'     => $this->pengaduan->kategori_label,
            'icon'         => 'bi-exclamation-circle-fill',
            'color'        => 'warning',
            'url'          => route('admin.pengaduan.show', $this->pengaduan->id),
        ];
    }
}