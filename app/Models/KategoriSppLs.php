<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriSppLs extends Model
{
    use HasFactory, TraitUuid, SoftDeletes;

    protected $table = 'kategori_spp_ls';
}
