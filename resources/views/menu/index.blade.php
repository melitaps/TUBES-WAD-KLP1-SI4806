@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold" style="color: var(--nra-green);">Manajemen Menu</h1>
        
        <div class="d-flex gap-2">
            <a href="{{ route('menu.export') }}" class="btn btn-outline-danger btn-lg">
                <i class="bi bi-file-pdf"></i> Export PDF
            </a>

            <button class="btn btn-nra-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                <i class="bi bi-plus-circle"></i> Tambah Menu
            </button>
        </div>
    </div>
    
    <div class="row g-4">
        @foreach($menu as $item)
        <div class="col-md-4">
            <div class="card menu-card card-custom">
                @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->nama_menu }}">
                @else
                <img src="https://via.placeholder.com/400x200/1a5c1a/ffffff?text={{ urlencode($item->nama_menu) }}" 
                     class="card-img-top" alt="{{ $item->nama_menu }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $item->nama_menu }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($item->deskripsi, 80) }}</p>
                    <span class="badge bg-secondary mb-2">{{ $item->kategori->nama_kategori }}</span>
                    <p class="price mb-3">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                        <button class="btn btn-warning btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $item->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <form action="{{ route('menu.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus menu ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded" alt="{{ $item->nama_menu }}">
                                @else
                                <img src="https://via.placeholder.com/400/1a5c1a/ffffff?text={{ urlencode($item->nama_menu) }}" 
                                     class="img-fluid rounded" alt="{{ $item->nama_menu }}">
                                @endif
                            </div>
                            <div class="col-md-7">
                                <h4 class="mb-3" style="color: var(--nra-green);">{{ $item->nama_menu }}</h4>
                                <p class="mb-3">{{ $item->deskripsi }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-secondary">{{ $item->kategori->nama_kategori }}</span>
                                </div>
                                <h5 class="mb-3" style="color: var(--nra-green);">Rp{{ number_format($item->harga, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="editMenuModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('menu.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="nama_menu" class="form-control" value="{{ $item->nama_menu }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control" value="{{ $item->harga }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ $item->deskripsi }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" class="form-select" required>
                                    @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gambar Menu</label>
                                @if($item->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $item->image) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                                </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
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
        @endforeach
    </div>
</div>

<div class="modal fade" id="addMenuModal" tabindex="-1">
    </div>

@push('scripts')
<script>
// Image preview script tetap sama
</script>
@endpush
@endsection