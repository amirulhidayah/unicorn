<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramDpa extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;

    protected $table = 'program_dpa';

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'program_id')->withTrashed();
    }
}
