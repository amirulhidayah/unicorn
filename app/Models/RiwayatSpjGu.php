<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSpjGu extends Model
{
    use HasFactory, TraitUuid;
    protected $table = 'riwayat_spj_gu';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'user_id')->withTrashed();
    }

    public function spjGu()
    {
        return $this->hasOne(SpjGu::class, 'id', 'spj_gu_id');
    }
}
