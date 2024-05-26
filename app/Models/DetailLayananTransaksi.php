<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLayananTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_layanan_transaksi';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'harga_jenis_layanan_id',
        'detail_transaksi_id',
    ];

    public function detailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class);
    }

    public function hargaJenisLayanan()
    {
        return $this->belongsTo(HargaJenisLayanan::class);
    }
}
