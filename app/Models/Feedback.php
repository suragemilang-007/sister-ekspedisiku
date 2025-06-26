<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    protected $primaryKey = 'id_feedback';
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'nomor_resi',
        'rating',
        'komentar',
        'created_at'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime'
    ];

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'nomor_resi');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uid = (string) str()::uuid();
        });
    }
}