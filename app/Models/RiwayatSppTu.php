<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSppTu extends Model
{
    use HasFactory;
    use TraitUuid;
    protected $table = 'riwayat_spp_tu';

    public function profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'user_id')->withTrashed();
    }

    public function sppTu()
    {
        return $this->hasOne(SppTu::class, 'id', 'spp_tu_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }
}
