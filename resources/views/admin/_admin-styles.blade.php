{{-- Simpan sebagai: resources/views/admin/_admin-styles.blade.php --}}
{{-- Gunakan dengan: @include('admin._admin-styles') --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<style>
    body,
    .card,
    th,
    td,
    input,
    select,
    button,
    label,
    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .page-header h5 {
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -.4px;
    }

    .page-header .sub {
        color: #94a3b8;
        font-size: .8rem;
        margin-top: .2rem;
    }

    /* ── Filter Bar ── */
    .filter-bar {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: .75rem;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
    }

    .filter-input,
    .filter-select {
        border: 1.5px solid #e2e8f0;
        border-radius: .55rem;
        padding: .5rem .9rem;
        font-size: .85rem;
        color: #334155;
        background: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color .2s;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #1a56db;
        box-shadow: 0 0 0 3px rgba(26, 86, 219, .08);
        outline: none;
        background: #fff;
    }

    .filter-input {
        min-width: 220px;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap .si {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: .85rem;
        pointer-events: none;
    }

    .search-wrap .filter-input {
        padding-left: 2.2rem;
    }

    /* ── Table ── */
    .data-table th {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #94a3b8;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: .9rem 1rem;
        background: #f8fafc;
        white-space: nowrap;
    }

    .data-table td {
        padding: .9rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc !important;
        font-size: .85rem;
        color: #334155;
    }

    .data-table tr:hover td {
        background: #f8faff;
    }

    .data-table tr:last-child td {
        border-bottom: none !important;
    }

    /* ── Badges ── */
    .status-badge {
        font-size: .7rem;
        font-weight: 700;
        padding: .28rem .65rem;
        border-radius: 2rem;
        letter-spacing: .03em;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }

    .badge-success {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-warning {
        background: #fef9c3;
        color: #92400e;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-gray {
        background: #f1f5f9;
        color: #64748b;
    }

    /* ── Avatar ── */
    .av {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .78rem;
        flex-shrink: 0;
    }

    /* ── Card wrapper ── */
    .data-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: .85rem;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(15, 23, 42, .05);
    }

    .data-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .data-card-header h6 {
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    /* ── Empty State ── */
    .empty-state {
        padding: 4rem 1rem;
        text-align: center;
    }

    .empty-state .empty-icon {
        font-size: 3rem;
        color: #e2e8f0;
        margin-bottom: .75rem;
    }

    .empty-state .empty-title {
        font-weight: 700;
        color: #94a3b8;
        font-size: .95rem;
    }

    .empty-state .empty-sub {
        color: #cbd5e1;
        font-size: .8rem;
        margin-top: .25rem;
    }

    /* ── Btn ── */
    .btn-primary-sm {
        background: linear-gradient(135deg, #1a56db, #1e429f);
        color: #fff;
        border: none;
        border-radius: .55rem;
        padding: .45rem 1rem;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
    }

    .btn-primary-sm:hover {
        opacity: .9;
        color: #fff;
        text-decoration: none;
    }

    .btn-primary-sm:focus,
    .btn-primary-sm:active {
        color: #fff;
        text-decoration: none;
    }

    code {
        background: #f1f5f9;
        color: #e11d48;
        padding: .15rem .4rem;
        border-radius: .3rem;
        font-size: .8rem;
    }
</style>
