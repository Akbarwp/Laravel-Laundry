<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaJenisLayanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'harga_jenis_layanan';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'harga',
        'jenis_satuan',
        'jenis_layanan_id',
        'jenis_pakaian_id',
        'cabang_id',
    ];

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    public function jenisPakaian()
    {
        return $this->belongsTo(JenisPakaian::class);
    }
}
