<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_order',
        'nama_pemesan',
        'no_hp',
        'alamat',
        'metode_pembayaran',
        'catatan_tambahan',
        'total_harga',
        'status'
    ];

    /**
     * Relasi:
     * 1 Order punya banyak OrderDetail
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Auto-generate nomor order
     * Format: ORD-YYYYMMDD-0001
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $todayCount = self::whereDate('created_at', today())->count() + 1;

            $order->no_order = 'ORD-' . date('Ymd') . '-' . str_pad(
                $todayCount,
                4,
                '0',
                STR_PAD_LEFT
            );
        });
    }
}