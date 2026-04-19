<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PendudukDihapus extends Notification
{
    use Queueable;

    public function __construct(
        public string $pendudukNama,
        public ?string $pendudukNik,
        public User $deletedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'penduduk_dihapus',
            'title'          => 'Data Penduduk Dihapus',
            'message'        => "{$this->deletedBy->name} menghapus data: {$this->pendudukNama}",
            'penduduk_nama'  => $this->pendudukNama,
            'penduduk_nik'   => $this->pendudukNik,
            'deleted_by_id'  => $this->deletedBy->id,
            'deleted_by_name'=> $this->deletedBy->name,
            'deleted_by_role'=> $this->deletedBy->role,
            'icon'           => 'bi-trash-fill',
            'color'          => 'danger',
            'url'            => route('admin.penduduk.index'),
        ];
    }
}
