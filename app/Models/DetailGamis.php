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
        'nama',
        'foto',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'telepon',
        'alamat',
        'mulai_kerja',
        'selesai_kerja',
        'user_id',
        'gamis_id',
    ];

    public function gamis()
    {
        return $this->belongsTo(Gamis::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
