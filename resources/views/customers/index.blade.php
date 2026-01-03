@extends('layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="container py-5">
    <!-- Stats Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-label">Total Pelanggan</div>
                <div class="stats-value">{{ $totalPelanggan }}</div>
                <div class="stats-subtext">Pelanggan</div>
            </div>
        </div>
    </div>
    
    <!-- Filter & Export -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customers.index') }}">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Cari pelanggan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="filter">
                            <option value="">7 hari terakhir</option>
                            <option value="30">30 hari terakhir</option>
                            <option value="all">Semua waktu</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-nra-primary">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('customers.export', ['type' => 'excel']) }}" class="btn btn-nra-outline flex-fill">
                                <i class="bi bi-download"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add Customer Button -->
    <div class="mb-3">
        <button class="btn btn-nra-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="bi bi-plus-circle"></i> Tambah Pelanggan
        </button>
    </div>
    
    <!-- Customer Table -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Nomor HP</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td><strong>C{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $customer->nama }}</td>
                            <td>{{ $customer->no_hp }}</td>
                            <td>{{ Str::limit($customer->alamat, 40) }}</td>
                            <td>
                                <button class="btn btn-success btn-sm" 
                                        onclick="showDetailModal({{ $customer->id }}, '{{ $customer->nama }}', '{{ $customer->no_hp }}', '{{ addslashes($customer->alamat) }}', '{{ $customer->wilayah->kota_kabupaten ?? '-' }}', '{{ $customer->wilayah->provinsi ?? '-' }}', {{ $customer->total_pesanan ?? 0 }}, {{ $customer->total_transaksi ?? 0 }})">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                                <button class="btn btn-warning btn-sm" 
                                        onclick="showEditModal({{ $customer->id }}, '{{ addslashes($customer->nama) }}', '{{ $customer->no_hp }}', '{{ addslashes($customer->alamat) }}', {{ $customer->wilayah_id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelanggan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Tidak ada data pelanggan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALS - OUTSIDE THE LOOP (NO FLICKERING!) -->
<!-- ============================================ -->

<!-- Detail Customer Modal - SINGLE INSTANCE -->
<div class="modal fade" id="detailCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="text-muted small">ID Pelanggan</label>
                    <div class="fw-bold" id="detail-id"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nama</label>
                    <div class="fw-bold" id="detail-nama"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nomor HP</label>
                    <div class="fw-bold" id="detail-hp"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Alamat</label>
                    <div class="fw-bold" id="detail-alamat"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Wilayah</label>
                    <div class="fw-bold" id="detail-wilayah"></div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="text-muted small">Total Pesanan</label>
                        <div class="fw-bold" id="detail-pesanan"></div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small">Total Transaksi</label>
                        <div class="fw-bold" id="detail-transaksi"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Customer Modal - SINGLE INSTANCE -->
<div class="modal fade" id="editCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCustomerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" id="edit-hp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="edit-alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah <span class="text-danger">*</span></label>
                        <select name="wilayah_id" id="edit-wilayah" class="form-select" required>
                            <option value="">Pilih Wilayah</option>
                            @php
                                $wilayahList = \App\Models\Wilayah::all();
                            @endphp
                            @foreach($wilayahList as $wilayah)
                            <option value="{{ $wilayah->id }}">
                                {{ $wilayah->kota_kabupaten }}, {{ $wilayah->provinsi }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-nra-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                        @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah <span class="text-danger">*</span></label>
                        <select name="wilayah_id" class="form-select @error('wilayah_id') is-invalid @enderror" required>
                            <option value="">Pilih Wilayah</option>
                            @foreach($wilayahList as $wilayah)
                            <option value="{{ $wilayah->id }}" {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>
                                {{ $wilayah->kota_kabupaten }}, {{ $wilayah->provinsi }}
                            </option>
                            @endforeach
                        </select>
                        @error('wilayah_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-nra-primary">
                        <i class="bi bi-save"></i> Simpan Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Show Detail Modal - Populate data dynamically
function showDetailModal(id, nama, hp, alamat, kota, provinsi, pesanan, transaksi) {
    document.getElementById('detail-id').textContent = 'C' + String(id).padStart(4, '0');
    document.getElementById('detail-nama').textContent = nama;
    document.getElementById('detail-hp').textContent = hp;
    document.getElementById('detail-alamat').textContent = alamat;
    document.getElementById('detail-wilayah').textContent = kota + ', ' + provinsi;
    document.getElementById('detail-pesanan').textContent = pesanan;
    document.getElementById('detail-transaksi').textContent = 'Rp' + Number(transaksi).toLocaleString('id-ID');
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('detailCustomerModal'));
    modal.show();
}

// Show Edit Modal - Populate data dynamically
function showEditModal(id, nama, hp, alamat, wilayahId) {
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-hp').value = hp;
    document.getElementById('edit-alamat').value = alamat;
    document.getElementById('edit-wilayah').value = wilayahId;
    
    // Update form action URL
    document.getElementById('editCustomerForm').action = '/customers/' + id;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
    modal.show();
}

// Auto show add modal if there are validation errors
@if($errors->any() && old('_method') === null)
    const addModal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
    addModal.show();
@endif

// Prevent modal backdrop stacking
document.addEventListener('hidden.bs.modal', function (event) {
    // Remove any leftover backdrops
    const backdrops = document.querySelectorAll('.modal-backdrop');
    if (backdrops.length > 1) {
        backdrops.forEach((backdrop, index) => {
            if (index > 0) backdrop.remove();
        });
    }
    
    // Reset body overflow
    if (document.querySelectorAll('.modal.show').length === 0) {
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
});
</script>
@endpush

@push('styles')
<style>
/* Prevent modal flickering */
.modal {
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}

.modal.fade .modal-dialog {
    transition: transform 0.2s ease-out;
}

/* Ensure smooth modal transitions */
.modal-backdrop {
    transition: opacity 0.15s linear;
}

/* Fix z-index issues */
.modal-backdrop.show {
    opacity: 0.5;
}
</style>
@endpush
@endsection