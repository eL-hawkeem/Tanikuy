@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Kelola Pengguna & Peran</h1>
        <p class="text-muted small m-0 mt-1">Daftar pengguna sistem Tanikuy dengan hak akses Administrator dan Kasir</p>
    </div>
    <div>
        <a href="{{ route('users.create') }}" class="btn btn-brand-lime">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>
</div>

<!-- KPI Mini Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-custom py-3 border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Total Pengguna</div>
                    <div class="h3 fw-bold text-dark m-0">{{ $users->total() }} Akun</div>
                </div>
                <div class="p-3 bg-light rounded-circle text-success">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom py-3 border-start border-4 border-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Administrator</div>
                    <div class="h3 fw-bold text-primary m-0">{{ $totalAdmins }} Akun</div>
                </div>
                <div class="p-3 bg-light rounded-circle text-primary">
                    <i class="bi bi-shield-lock-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom py-3 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Kasir (POS)</div>
                    <div class="h3 fw-bold text-warning m-0">{{ $totalKasir }} Akun</div>
                </div>
                <div class="p-3 bg-light rounded-circle text-warning">
                    <i class="bi bi-cart-check-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-card-custom">
    <!-- Filter Bar -->
    <div class="table-card-header">
        <form action="{{ route('users.index') }}" method="GET" class="row g-2 w-100 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau email pengguna..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="kasir" {{ $role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-brand-forest w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if($search || $role)
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Pengguna</th>
                    <th>Email</th>
                    <th>Peran (Role)</th>
                    <th>Hak Akses</th>
                    <th>Terdaftar Sejak</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background-color: {{ $user->isAdmin() ? '#072F1F' : '#EAB308' }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        <span>{{ $user->name }}</span>
                                        @if(auth()->id() === $user->id)
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">Anda</span>
                                        @endif
                                    </div>
                                    <div class="text-muted small">ID: #{{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark">{{ $user->email }}</span>
                        </td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="bi bi-shield-check me-1"></i> Administrator
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                    <i class="bi bi-cart-fill me-1"></i> Kasir
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->isAdmin())
                                <span class="small text-muted">Akses Penuh Seluruh Sistem & Laporan</span>
                            @else
                                <span class="small text-muted">Akses POS, Riwayat & Cek Stok</span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light border" title="Edit Pengguna">
                                    <i class="bi bi-pencil text-warning"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Pengguna">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </span>
            <div>
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
