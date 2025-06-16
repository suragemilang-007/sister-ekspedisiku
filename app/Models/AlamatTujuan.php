<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlamatTujuan extends Model
{
    use HasFactory;

    protected $table = 'alamat_tujuan';
    protected $primaryKey = 'id_alamat_tujuan';
    public $timestamps = false;

    protected $fillable = [
        'nama_penerima',
        'no_hp',
        'alamat_lengkap',
        'kecematan',
        'kode_pos',
        'telepon',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function pengiriman(): HasMany
    {
        return $this->hasMany(Pengiriman::class, 'id_alamat_tujuan');
    }
}