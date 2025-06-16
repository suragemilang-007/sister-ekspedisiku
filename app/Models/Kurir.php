<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurir extends Model
{
    use HasFactory;

    protected $table = 'kurir';
    protected $primaryKey = 'id_kurir';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'tgl_lahir',
        'nohp',
        'alamat',
        'kelamin',
        'kendaraan',
        'sandi_hash',
        'status',
        'created_at'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'created_at' => 'datetime',
        'kelamin' => 'string',
        'kendaraan' => 'string',
        'status' => 'string'
    ];

    public function penugasan(): HasMany
    {
        return $this->hasMany(PenugasanKurir::class, 'id_kurir');
    }
}