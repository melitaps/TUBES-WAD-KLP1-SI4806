<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $fillable = [
        'nama_menu',
        'harga',
        'deskripsi',
        'kategori_id',
        'image',
    ];

    //menu belongsTo kategori//
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}