<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'nama',
        'email',
        'tgl_lahir',
        'nohp',
        'alamat',
        'kelamin',
        'sandi_hash',
        'peran',
        'created_at'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'created_at' => 'datetime',
        'kelamin' => 'string',
        'peran' => 'string'
    ];

    public function pengiriman(): HasMany
    {
        return $this->hasMany(Pengiriman::class, 'id_pengirim');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_pengguna');
    }

    public function pelacakan(): HasMany
    {
        return $this->hasMany(Pelacakan::class, 'updated_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uid = (string) str::uuid();
        });
    }

}