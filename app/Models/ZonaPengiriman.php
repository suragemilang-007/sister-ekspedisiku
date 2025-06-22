<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZonaPengiriman extends Model
{
    use HasFactory;

    protected $table = 'zona_pengiriman';
    protected $primaryKey = 'id_zona';
    public $timestamps = false;

    protected $fillable = [
        'asal',
        'tujuan',
        'id_layanan',
        'biaya_zona'
    ];

    protected $casts = [
        'biaya_zona' => 'float'
    ];

    public function layananPaket(): BelongsTo
    {
        return $this->belongsTo(LayananPaket::class, 'id_layanan');
    }
    public function pengiriman(): HasMany
    {
        return $this->hasMany(Pengiriman::class, 'id_zona');
    }
}