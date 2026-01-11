<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada untuk delete image
use Barryvdh\DomPDF\Facade\Pdf; // TAMBAHKAN INI UNTUK PDF

class MenuController extends Controller
{
    public function halamanMenu()
    {
        $menu = Menu::with('kategori')->get();
        $kategori = Kategori::all();
        return view('menu.index', compact('menu', 'kategori'));
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
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,HEIC|max:2048'
        ]);

        $data = $request->all();
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $data['image'] = $imagePath;
        }

        Menu::create($data);

        return redirect()->route('menu.index')
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
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,HEIC|max:2048'
        ]);

        $data = $request->all();
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $data['image'] = $imagePath;
        }
        $menu->update($data);

        return redirect()->route('menu.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    //hpus//
    public function destroy($id)
    {
        Menu::destroy($id);

        return redirect()->route('menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }

    /**
     * FITUR TAMBAHAN: EXPORT PDF
     */
    public function export()
    {
        // Mengambil semua data menu beserta kategorinya
        $menus = Menu::with('kategori')->get();

        // Load view khusus export dan kirim data $menus
        $pdf = Pdf::loadView('menu.export-pdf', compact('menus'))
                  ->setPaper('a4', 'portrait');

        // Download file PDF
        return $pdf->download('daftar-menu-' . date('d-m-Y') . '.pdf');
    }
}