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
    protected $table = 'profil';

    public function biroOrganisasi()
    {
        return $this->belongsTo(BiroOrganisasi::class, 'biro_organisasi_id', 'id')->withTrashed();
    }
}
