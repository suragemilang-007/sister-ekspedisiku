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
        'id_alamat_penjemputan',
        'total_biaya',
        'id_zona',
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
        return $this->belongsTo(Pengguna::class, 'nomor_resi');
    }

    public function alamatTujuan(): BelongsTo
    {
        return $this->belongsTo(AlamatTujuan::class, 'id_alamat_tujuan', 'uid');
    }

    public function alamatPenjemputan(): BelongsTo
    {
        return $this->belongsTo(AlamatPenjemputan::class, 'id_alamat_penjemputan', 'uid');
    }

    public function zonaPengiriman(): BelongsTo
    {
        return $this->belongsTo(ZonaPengiriman::class, 'id_zona');
    }

    public function layananPaket()
    {
        return $this->belongsToMany(
            LayananPaket::class,
            ZonaPengiriman::class,
            'id_zona',
            'id_layanan',
            'id_zona',
            'id_layanan'
        )->withPivot('id_zona');
    }


    public function pelacakan(): HasMany
    {
        return $this->hasMany(Pelacakan::class, 'id_pengiriman');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengirim');
    }


    public function penugasanKurir(): HasOne
    {
        return $this->HasOne(PenugasanKurir::class, 'id_pengiriman');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, 'nomor_resi', 'nomor_resi');
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
            'id_pengiriman',
            'id_kurir',
            'id_pengiriman',
            'id_kurir'
        );
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'MENUNGGU KONFIRMASI':
                return 'warning';
            case 'DIPROSES':
                return 'primary';
            case 'DIBAYAR':
                return 'info';
            case 'DIKIRIM':
                return 'success';
            case 'DITERIMA':
                return 'primary';
            case 'DIBATALKAN':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}