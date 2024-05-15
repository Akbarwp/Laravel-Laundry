<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UMR extends Model
{
    use HasFactory;

    protected $table = 'umr';
    protected $primaryKey = 'id';
    public $incrementing = "true";
    public $timestamps = "true";
    protected $fillable = [
        'regional',
        'upah',
        'tahun',
        'is_used',
    ];
}
