<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanSpjGu extends Model
{
    use HasFactory, TraitUuid;
    protected $table = 'kegiatan_spj_gu';

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function spjGu()
    {
        return $this->belongsTo(SpjGu::class);
    }
}
