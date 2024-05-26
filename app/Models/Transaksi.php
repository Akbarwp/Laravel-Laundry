<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'nota_layanan',
        'nota_pelanggan',
        'waktu',
        'total_biaya_layanan',
        'total_biaya_prioritas',
        'total_bayar_akhir',
        'jenis_pembayaran',
        'bayar',
        'kembalian',
        'pelanggan_id',
        'pegawai_laundry_id',
        'gamis_id',
        'cabang_id',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function layananPrioritas()
    {
        return $this->belongsTo(LayananPrioritas::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pegawaiLaundry()
    {
        return $this->belongsTo(PegawaiLaundry::class);
    }

    public function gamis()
    {
        return $this->belongsTo(DetailGamis::class);
    }
}
