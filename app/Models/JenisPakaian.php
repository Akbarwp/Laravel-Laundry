<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPakaian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_pakaian';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'deskripsi',
        'lokasi',
        'alamat',
        'cabang_id',
    ];

    public function hargaJenisLayanan()
    {
        return $this->hasMany(HargaJenisLayanan::class);
    }
}
