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
        return $this->belongsTo(KegiatanSpp::class, 'kegiatan_spp_id', 'id')->withTrashed();
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'tahun_id', 'id')->withTrashed();
    }

    public function biroOrganisasi()
    {
        return $this->belongsTo(BiroOrganisasi::class, 'biro_organisasi_id', 'id')->withTrashed();
    }
}
