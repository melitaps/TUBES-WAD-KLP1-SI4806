@extends('layouts.app')

@section('content')
<h4>Menu Ayam</h4>

<a href="/menu/create" class="btn btn-primary mb-3">Tambah Menu</a>

<table class="table table-bordered">
    <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

    @foreach($menus as $menu)
    <tr>
        <td>{{ $menu->nama_menu }}</td>
        <td>{{ $menu->kategori->nama_kategori }}</td>
        <td>{{ $menu->harga }}</td>
        <td>{{ $menu->stok }}</td>
        <td>
            <a href="/menu/{{ $menu->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
            <form action="/menu/{{ $menu->id }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
