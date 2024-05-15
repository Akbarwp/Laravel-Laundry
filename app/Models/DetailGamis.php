<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGamis extends Model
{
    use HasFactory;

    protected $table = 'detail_gamis';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'foto',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'golongan_darah',
        'status_keluarga',
        'telepon',
        'alamat',
        'mulai_kerja',
        'selesai_kerja',
        'user_id',
    ];

    public function gamis()
    {
        return $this->belongsTo(Gamis::class);
    }
}
