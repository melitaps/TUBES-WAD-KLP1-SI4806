<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $fillable = ['provinsi', 'kota_kabupaten'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'wilayah_id');
    }
}