<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanSppTu extends Model
{
    use HasFactory, TraitUuid;
    protected $table = 'kegiatan_spp_tu';

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class)->withTrashed();
    }

    public function sppTu()
    {
        return $this->belongsTo(SppTu::class);
    }
}
