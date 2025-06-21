<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $timestamps = false;

    protected $fillable = [
        'id_pengirim',
        'id_alamat_tujuan',
        'total_biaya',
        'id_layanan',
        'status',
        'nomor_resi',
        'catatan_opsional',
        'keterangan_batal',
        'foto_barang',
        'foto_bukti_sampai',
        'tanggal_sampai',
        'created_at'
    ];

    protected $casts = [
        'total_biaya' => 'float',
        'tanggal_sampai' => 'datetime',
        'created_at' => 'datetime',
        'status' => 'string'
    ];

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengirim');
    }

    public function alamatTujuan(): BelongsTo
    {
        return $this->belongsTo(AlamatTujuan::class, 'id_alamat_tujuan');
    }

    public function layananPaket(): BelongsTo
    {
        return $this->belongsTo(LayananPaket::class, 'id_layanan');
    }

    public function pelacakan(): HasMany
    {
        return $this->hasMany(Pelacakan::class, 'id_pengiriman');
    }

    public function penugasanKurir(): HasOne
    {
        return $this->hasOne(PenugasanKurir::class, 'id_pengiriman');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, 'id_pengiriman');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pengiriman');
    }
    public function kurir()
    {
        return $this->hasOneThrough(
            Kurir::class,
            PenugasanKurir::class,
            'id_pengiriman',  // Foreign key di penugasan_kurir
            'id_kurir',       // Foreign key di kurir
            'id_pengiriman',  // Local key di pengiriman
            'id_kurir'        // Local key di penugasan_kurir
        );
    }
}