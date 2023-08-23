<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppGu extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'spp_gu';

    public function kegiatanDpa()
    {
        return $this->belongsTo(KegiatanDpa::class)->withTrashed();
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class)->withTrashed();
    }

    public function sekretariatDaerah()
    {
        return $this->belongsTo(SekretariatDaerah::class)->withTrashed();
    }

    public function dokumenSppGu()
    {
        return $this->hasMany(DokumenSppGu::class, 'spp_gu_id', 'id')->orderBy('created_at', 'asc');
    }

    public function riwayatSppGu()
    {
        return $this->hasMany(RiwayatSppGu::class, 'spp_gu_id', 'id')->orderBy('created_at', 'asc');
    }

    public function spjGu()
    {
        return $this->belongsTo(SpjGu::class);
    }
}
