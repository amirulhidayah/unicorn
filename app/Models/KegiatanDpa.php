<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanDpa extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;

    protected $table = 'kegiatan_dpa';

    public function spd()
    {
        return $this->hasMany(Spd::class, 'kegiatan_dpa_id')->withTrashed();
    }

    public function programDpa()
    {
        return $this->belongsTo(ProgramDpa::class, 'program_dpa_id')->withTrashed();
    }
}
