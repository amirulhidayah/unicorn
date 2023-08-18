<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppLs extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'spp_ls';

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

    public function riwayatSppLs()
    {
        return $this->hasMany(RiwayatSppLs::class, 'spp_ls_id', 'id')->orderBy('created_at', 'asc');
    }

    public function kategoriSppLs()
    {
        return $this->belongsTo(KategoriSppLs::class);
    }
}
