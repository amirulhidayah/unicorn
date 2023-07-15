<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanSpp extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;

    protected $table = 'kegiatan_spp';

    public function programSpp()
    {
        return $this->belongsTo(ProgramSpp::class, 'program_spp_id', 'id')->withTrashed();
    }
}
