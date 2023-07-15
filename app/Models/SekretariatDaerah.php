<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SekretariatDaerah extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;
    protected $table = 'sekretariat_daerah';
}
