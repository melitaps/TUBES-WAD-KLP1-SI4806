@extends('layouts.app')

@section('title', 'Laporan & Statistik')

@section('content')
<div class="container py-5">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-label">Total Pelanggan</div>
                <div class="stats-value">{{ $totalPesanan ?? 1 }}</div>
                <div class="stats-subtext">Pelanggan</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="stats-label">Total Pemasukan</div>
                <div class="stats-value">Rp{{ number_format($totalPendapatan ?? 15000, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Filter & Export -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row align-items-center">
                <div class="col-md-3">
                    <select name="year" class="form-select">
                        <option value="2024" {{ ($year ?? date('Y')) == 2024 ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ ($year ?? date('Y')) == 2025 ? 'selected' : '' }}>2025</option>
                        <option value="2026" {{ ($year ?? date('Y')) == 2026 ? 'selected' : '' }}>2026</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ ($month ?? date('n')) == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ $date ?? '' }}">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-nra-primary flex-fill">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <a href="{{ route('reports.export', request()->all()) }}" class="btn btn-nra-outline flex-fill">
                            <i class="bi bi-download"></i> Export
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card card-custom">
        <div class="card-body">
            <h5 class="mb-4">Detail Laporan</h5>
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
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>C{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $order->nama_pemesan }}</td>
                            <td>{{ $order->no_hp ?? '082111444555' }}</td>
                            <td>{{ $order->alamat ?? 'Jalan Sukabirus' }}</td>
                            <td>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                                <button class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Tidak ada data untuk periode ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
