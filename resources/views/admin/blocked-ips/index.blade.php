@extends('layouts.app')
@section('title', 'IP Terblokir')
@section('page-title', 'IP Terblokir')

@push('styles')
@include('admin._admin-styles')
<style>
    .modal-custom {
        background: #fff; border-radius: 1rem; padding: 1.75rem;
        box-shadow: 0 20px 60px rgba(15,23,42,.15);
        border: 1px solid #f1f5f9; max-width: 440px; width: 100%;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h5>IP Terblokir</h5>
        <div class="sub">Kelola alamat IP yang diblokir dari sistem</div>
    </div>
    <button class="btn-primary-sm" data-bs-toggle="modal" data-bs-target="#blockModal">
        <i class="bi bi-ban"></i> Blokir IP Baru
    </button>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h6><i class="bi bi-ban me-2" style="color:#dc2626;"></i>Daftar IP Terblokir</h6>
        <span style="font-size:.78rem;color:#94a3b8;">{{ $blockedIps->total() }} entri</span>
    </div>
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">IP Address</th>
                    <th>Alasan</th>
                    <th>Tipe Blokir</th>
                    <th>Berlaku Sampai</th>
                    <th>Diblokir Pada</th>
                    <th class="pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blockedIps as $ip)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $ip->isActive() ? '#ef4444' : '#94a3b8' }};flex-shrink:0;"></div>
                            <code style="font-size:.85rem;">{{ $ip->ip_address }}</code>
                        </div>
                    </td>
                    <td style="max-width:200px;">
                        <span style="font-size:.82rem;color:#475569;">{{ $ip->reason ?? '—' }}</span>
                    </td>
                    <td>
                        @if($ip->is_permanent)
                            <span class="status-badge badge-danger"><i class="bi bi-infinity"></i>Permanen</span>
                        @else
                            <span class="status-badge badge-warning"><i class="bi bi-clock"></i>Sementara</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem;color:#64748b;">
                        @if($ip->is_permanent)
                            <span style="color:#dc2626;font-weight:600;">Selamanya</span>
                        @elseif($ip->blocked_until)
                            <div>{{ $ip->blocked_until->format('d M Y, H:i') }}</div>
                            @if($ip->blocked_until->isFuture())
                                <div style="color:#f59e0b;font-size:.72rem;">{{ $ip->blocked_until->diffForHumans() }}</div>
                            @else
                                <div style="color:#94a3b8;font-size:.72rem;">Sudah berakhir</div>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:#64748b;">
                        <div>{{ $ip->created_at->format('d M Y') }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;">{{ $ip->created_at->format('H:i') }}</div>
                    </td>
                    <td class="pe-4">
                        <form method="POST" action="{{ route('admin.blocked-ips.destroy', $ip->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="padding:.35rem .7rem;border:1.5px solid #fca5a5;border-radius:.45rem;background:#fff5f5;color:#dc2626;font-size:.8rem;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;font-family:inherit;"
                                onclick="return confirm('Buka blokir IP {{ $ip->ip_address }}?')">
                                <i class="bi bi-unlock"></i> Buka Blokir
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="empty-title">Tidak ada IP yang diblokir</div>
                        <div class="empty-sub">Sistem aman, tidak ada IP mencurigakan</div>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($blockedIps->hasPages())
    <div style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:.78rem;color:#94a3b8;">{{ $blockedIps->total() }} total</span>
        {{ $blockedIps->links() }}
    </div>
    @endif
</div>

{{-- Modal Blokir IP --}}
<div class="modal fade" id="blockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:1rem;overflow:hidden;box-shadow:0 20px 60px rgba(15,23,42,.15);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h6 class="fw-700 mb-0" style="color:#0f172a;font-family:'Plus Jakarta Sans',sans-serif;">
                    <i class="bi bi-ban me-2 text-danger"></i>Blokir IP Address
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form method="POST" action="{{ route('admin.blocked-ips.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label style="font-size:.82rem;font-weight:700;color:#374151;display:block;margin-bottom:.4rem;">IP ADDRESS</label>
                        <input type="text" name="ip_address" class="filter-input w-100 @error('ip_address') is-invalid @enderror"
                            placeholder="contoh: 192.168.1.1" required>
                        @error('ip_address') <div style="color:#dc2626;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label style="font-size:.82rem;font-weight:700;color:#374151;display:block;margin-bottom:.4rem;">ALASAN</label>
                        <input type="text" name="reason" class="filter-input w-100" placeholder="Alasan pemblokiran...">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:.82rem;font-weight:700;color:#374151;display:block;margin-bottom:.4rem;">TIPE BLOKIR</label>
                        <select name="is_permanent" class="filter-select w-100" id="tipeSel">
                            <option value="0">Sementara (dengan batas waktu)</option>
                            <option value="1">Permanen</option>
                        </select>
                    </div>
                    <div class="mb-4" id="untilWrap">
                        <label style="font-size:.82rem;font-weight:700;color:#374151;display:block;margin-bottom:.4rem;">BERLAKU SAMPAI</label>
                        <input type="datetime-local" name="blocked_until" class="filter-select w-100" min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    <button type="submit" class="btn-primary-sm w-100" style="justify-content:center;">
                        <i class="bi bi-ban"></i> Blokir IP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('tipeSel')?.addEventListener('change', function() {
        document.getElementById('untilWrap').style.display = this.value === '1' ? 'none' : 'block';
    });
</script>
@endpush
