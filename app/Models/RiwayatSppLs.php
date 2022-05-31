<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSppLs extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'riwayat_spp_ls';
}
