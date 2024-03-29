<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppTu extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'spp_tu';

    public function riwayatSppTu()
    {
        return $this->hasMany(RiwayatSppTu::class, 'spp_tu_id', 'id')->orderBy('created_at', 'asc');
    }

    public function dokumenSppTu()
    {
        return $this->hasMany(DokumenSppTu::class, 'spp_tu_id', 'id')->orderBy('created_at', 'asc');
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

    public function kegiatanSppTu()
    {
        return $this->hasMany(KegiatanSppTu::class);
    }
}
