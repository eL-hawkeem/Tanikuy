@extends('layouts.app')

@section('title', 'Edit Pengguna ' . $user->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Edit Pengguna: {{ $user->name }}</h1>
        <p class="text-muted small m-0 mt-1">Perbarui profil dan peran akses pengguna</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Peran (Role) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Pilih Peran (Role) <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="card h-100 p-3 border cursor-pointer {{ old('role', $user->role) === 'admin' ? 'border-success bg-light' : '' }}" style="cursor: pointer;">
                                <div class="d-flex align-items-start gap-2">
                                    <input type="radio" name="role" value="admin" class="form-check-input mt-1" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }} required>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-shield-check text-success"></i> Administrator
                                        </div>
                                        <div class="small text-muted mt-1">Akses penuh ke seluruh modul, master data barang/supplier, kalkulasi EOQ, laporan laba, dan kelola akun.</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="card h-100 p-3 border cursor-pointer {{ old('role', $user->role) === 'kasir' ? 'border-warning bg-light' : '' }}" style="cursor: pointer;">
                                <div class="d-flex align-items-start gap-2">
                                    <input type="radio" name="role" value="kasir" class="form-check-input mt-1" {{ old('role', $user->role) === 'kasir' ? 'checked' : '' }} required>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                            <i class="bi bi-cart-fill text-warning"></i> Kasir
                                        </div>
                                        <div class="small text-muted mt-1">Fokus operasional penjualan POS, pencatatan transaksi, cetak struk/faktur, dan pengecekan stok katalog barang.</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('role')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ganti Password (Opsional) -->
                <div class="p-3 bg-light rounded-3 mb-4 border">
                    <div class="fw-semibold text-dark mb-1">
                        <i class="bi bi-key-fill text-muted me-1"></i> Ubah Kata Sandi (Opsional)
                    </div>
                    <div class="small text-muted mb-3">Biarkan kosong jika Anda tidak ingin mengubah kata sandi pengguna ini.</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-semibold text-dark">Kata Sandi Baru</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label small fw-semibold text-dark">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi kata sandi">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-brand-lime px-4">
                        <i class="bi bi-check-lg"></i> Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
