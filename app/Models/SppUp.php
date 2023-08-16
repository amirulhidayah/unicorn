<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppUp extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'spp_up';

    public function riwayatSppUp()
    {
        return $this->hasMany(RiwayatSppUp::class, 'spp_up_id', 'id')->orderBy('created_at', 'asc');
    }

    public function dokumenSppUp()
    {
        return $this->hasMany(DokumenSppUp::class, 'spp_up_id', 'id')->orderBy('created_at', 'asc');
    }


    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id')->withTrashed();
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'tahun_id', 'id')->withTrashed();
    }

    public function sekretariatDaerah()
    {
        return $this->belongsTo(SekretariatDaerah::class, 'sekretariat_daerah_id', 'id')->withTrashed();
    }
}
