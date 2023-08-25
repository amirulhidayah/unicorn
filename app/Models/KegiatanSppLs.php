<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanSppLs extends Model
{
    use HasFactory, TraitUuid;
    protected $table = 'kegiatan_spp_ls';

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class)->withTrashed();
    }

    public function sppLs()
    {
        return $this->belongsTo(SppLs::class);
    }
}
