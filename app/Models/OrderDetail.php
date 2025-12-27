<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Menu;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'jumlah',
        'harga',
        'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'integer',
        'subtotal' => 'integer'
    ];
}