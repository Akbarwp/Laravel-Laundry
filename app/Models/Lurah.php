<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lurah extends Model
{
    use HasFactory;

    protected $table = 'lurah';
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
        'mulai_jabatan',
        'selesai_jabatan',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
