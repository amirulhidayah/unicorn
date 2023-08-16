<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spd extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'spd';

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id')->withTrashed();
    }

    public function SekretariatDaerah()
    {
        return $this->belongsTo(SekretariatDaerah::class, 'sekretariat_daerah_id')->withTrashed();
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'tahun_id')->withTrashed();
    }
}
