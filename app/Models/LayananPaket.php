<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananPaket extends Model
{
    use HasFactory;

    protected $table = 'layanan_paket';
    protected $primaryKey = 'id_layanan';
    public $timestamps = false;

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'min_berat',
        'max_berat',
        'harga_dasar'
    ];

    protected $casts = [
        'min_berat' => 'float',
        'max_berat' => 'float',
        'harga_dasar' => 'float'
    ];



    public function zonaPengiriman(): HasMany
    {
        return $this->hasMany(ZonaPengiriman::class, 'id_layanan');
    }
}