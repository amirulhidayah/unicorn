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

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class)->withTrashed();
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class)->withTrashed();
    }

    public function biroOrganisasi()
    {
        return $this->belongsTo(BiroOrganisasi::class)->withTrashed();
    }

    public function dokumenSppGu()
    {
        return $this->hasMany(DokumenSppGu::class, 'spp_gu_id', 'id')->orderBy('created_at', 'asc');
    }

    public function riwayatSppGu()
    {
        return $this->hasMany(RiwayatSppGu::class, 'spp_gu_id', 'id')->orderBy('created_at', 'asc');
    }
}
