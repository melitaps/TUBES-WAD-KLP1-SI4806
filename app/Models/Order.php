<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemesan',
        'no_hp',
        'alamat',
        'metode_pembayaran',
        'catatan',
        'total_harga',
        'status'
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}

