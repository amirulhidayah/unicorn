<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;

    protected $table = 'kegiatan';

    public function spd()
    {
        return $this->hasMany(Spd::class, 'kegiatan_id')->withTrashed();
    }

    public function sppGu()
    {
        return $this->hasMany(SppGu::class, 'kegiatan_id');
    }
    public function sppLs()
    {
        return $this->hasMany(SppLs::class, 'kegiatan_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id')->withTrashed();
    }
}
