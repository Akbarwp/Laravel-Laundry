<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_layanan';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nama',
        'deskripsi',
        'for_gamis',
        'cabang_id',
    ];

    public function hargaJenisLayanan()
    {
        return $this->hasMany(HargaJenisLayanan::class);
    }
}
