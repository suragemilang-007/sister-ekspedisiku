<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlamatPenjemputan extends Model
{
    use HasFactory;

    protected $table = 'alamat_penjemputan';
    protected $primaryKey = 'id_alamat_penjemputan';
    public $timestamps = false;

    protected $fillable = [
        'id_pengirim',
        'nama_pengirim',
        'no_hp',
        'alamat_lengkap',
        'kecamatan',
        'kode_pos',
        'keterangan_alamat',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke pengguna (pengirim)
     */
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengirim');
    }
    public function pengiriman(): HasMany
    {
        return $this->hasMany(Pengiriman::class, 'id_alamat_penjemputan');
    }
}