<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'santri_id',
        'periode_id',
        'sakit',
        'izin',
        'alpha',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
