<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
class MenuController extends Controller
{
    public function index()
    {
        return Menu::with('kategori')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_menu' => 'required',
            'harga' => 'required|integer',
            'deskripsi' => 'required'
        ]);

        return Menu::create($request->all());
    }

    public function show($id)
    {
        return Menu::with('kategori')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->update($request->all());
        return $menu;
    }

    public function destroy($id)
    {
        Menu::destroy($id);
        return response()->json(['message' => 'Menu deleted']);
    }
}