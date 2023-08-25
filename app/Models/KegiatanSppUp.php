<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanSppUp extends Model
{
    use HasFactory, TraitUuid;
    protected $table = 'kegiatan_spp_up';

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function sppUp()
    {
        return $this->belongsTo(SppUp::class);
    }
}
