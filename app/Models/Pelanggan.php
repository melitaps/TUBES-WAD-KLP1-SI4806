<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'nomor_hp',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district_id',
        'district_name',
        'village_id',
        'village_name',
        'alamat',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi: Pelanggan hasMany Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'pelanggan_id');
    }

    /**
     * Accessor untuk alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        $alamat = $this->alamat;
        
        if ($this->village_name) {
            $alamat .= ', ' . $this->village_name;
        }
        if ($this->district_name) {
            $alamat .= ', ' . $this->district_name;
        }
        $alamat .= ', ' . $this->city_name . ', ' . $this->province_name;
        
        return $alamat;
    }

    /**
     * Accessor untuk format nomor HP
     */
    public function getFormattedNomorHpAttribute()
    {
        $nomor = $this->nomor_hp;
        
        // Format: 0812-3456-7890
        if (strlen($nomor) >= 10) {
            return substr($nomor, 0, 4) . '-' . 
                   substr($nomor, 4, 4) . '-' . 
                   substr($nomor, 8);
        }
        
        return $nomor;
    }

    /**
     * Scope untuk pencarian pelanggan
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', "%{$search}%")
                     ->orWhere('nomor_hp', 'like', "%{$search}%")
                     ->orWhere('alamat', 'like', "%{$search}%");
    }
}