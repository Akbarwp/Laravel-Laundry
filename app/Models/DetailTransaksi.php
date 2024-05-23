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
        'jenis_satuan',
        'harga_akhir',
        'total_harga_akhir',
        'total_harga_prioritas',
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
