<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiroOrganisasi extends Model
{
    use HasFactory;
    use TraitUuid;
    use SoftDeletes;
    protected $table = 'biro_organisasi';
}
