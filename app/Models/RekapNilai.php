<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapNilai extends Model
{
    protected $table = 'rekap_nilai';
    protected $fillable = [
        'santri_id',
        'periode_id',
        'kelas_id',
        'total_nilai',
        'rata_rata',
        'peringkat',
        'keputusan',
        'catatan',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
