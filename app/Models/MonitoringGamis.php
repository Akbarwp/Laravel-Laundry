<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringGamis extends Model
{
    use HasFactory;

    protected $table = 'monitoring_gamis';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'upah',
        'status',
        'bulan',
        'tahun',
        'detail_gamis_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(DetailGamis::class);
    }
}
