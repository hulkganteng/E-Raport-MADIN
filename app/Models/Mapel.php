<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $guarded = [];

    public function kelas_mapel()
    {
        return $this->hasMany(KelasMapel::class);
    }
}
