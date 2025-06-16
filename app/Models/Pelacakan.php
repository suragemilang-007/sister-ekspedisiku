<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelacakan extends Model
{
    use HasFactory;

    protected $table = 'pelacakan';
    protected $primaryKey = 'id_pelacakan';
    public $timestamps = false;

    protected $fillable = [
        'id_pengiriman',
        'status',
        'lokasi',
        'updated_by',
        'updated_at'
    ];

    protected $casts = [
        'updated_at' => 'datetime'
    ];

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'id_pengiriman');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'updated_by');
    }
}