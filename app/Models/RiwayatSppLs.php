<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSppLs extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'riwayat_spp_ls';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'user_id')->withTrashed();
    }

    public function sppLs()
    {
        return $this->hasOne(SppLs::class, 'id', 'spp_ls_id');
    }
}
