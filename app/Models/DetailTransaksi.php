<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'total_pakaian',
        'harga_layanan_akhir',
        'total_biaya_layanan',
        'total_biaya_prioritas',
        'transaksi_id',
    ];

    public function detailLayananTransaksi()
    {
        return $this->hasMany(DetailLayananTransaksi::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
