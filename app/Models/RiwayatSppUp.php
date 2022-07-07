<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSppUp extends Model
{
    use HasFactory;
    use TraitUuid;
    protected $table = 'riwayat_spp_up';

    public function profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'user_id')->withTrashed();
    }

    public function sppUp()
    {
        return $this->hasOne(SppUp::class, 'id', 'spp_up_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }
}
