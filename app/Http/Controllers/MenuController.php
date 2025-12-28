<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;
use App\Models\Kategori;

class MenuController extends Controller
{
    public function halamanMenu()
    {
        return view('welcome', [
            'menu' => Menu::with('kategori')->get(),
            'kategori' => Kategori::all()
        ]);
    }

    //semua//
    public function index()
    {
        return response()->json(
            Menu::with('kategori')->get()
        );
    }

    //tambah//
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'   => 'required',
            'harga'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        Menu::create($request->all());

        return redirect('/')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    //detail//
    public function show($id)
    {
        return response()->json(
            Menu::with('kategori')->findOrFail($id)
        );
    }

    //update//
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_menu'   => 'required',
            'harga'       => 'required|numeric|min:0',
            'deskripsi'   => 'nullable',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        $menu->update($request->all());

        return redirect('/')
            ->with('success', 'Menu berhasil diperbarui');
    }

    //hpus//
    public function destroy($id)
    {
        Menu::destroy($id);

        return redirect('/')
            ->with('success', 'Menu berhasil dihapus');
    }
}