<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $guarded = [];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
