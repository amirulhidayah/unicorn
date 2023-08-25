<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profil extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;
    protected $table = 'profil';

    public function sekretariatDaerah()
    {
        return $this->belongsTo(SekretariatDaerah::class, 'sekretariat_daerah_id', 'id')->withTrashed();
    }
}
