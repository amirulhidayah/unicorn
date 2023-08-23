<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpjGu extends Model
{
    use HasFactory, TraitUuid;
    protected $table = 'spj_gu';

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

    public function dokumenSppLs()
    {
        return $this->hasMany(DokumenSppLs::class, 'spp_ls_id', 'id')->orderBy('created_at', 'asc');
    }

    public function riwayatSpjGu()
    {
        return $this->hasMany(RiwayatSpjGu::class, 'spj_gu_id', 'id')->orderBy('created_at', 'asc');
    }

    public function kegiatanSpjGu()
    {
        return $this->hasMany(KegiatanSpjGu::class);
    }

    public function sppGu()
    {
        return $this->hasOne(SppGu::class);
    }
}
