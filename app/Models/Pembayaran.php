<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = false;

    protected $fillable = [
        'id_pengiriman',
        'metode',
        'status',
        'jumlah_bayar',
        'waktu_bayar'
    ];

    protected $casts = [
        'jumlah_bayar' => 'float',
        'waktu_bayar' => 'datetime',
        'metode' => 'string',
        'status' => 'string'
    ];

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman');
    }
}