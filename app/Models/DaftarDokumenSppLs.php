<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarDokumenSppLs extends Model
{
    use HasFactory;
    use TraitUuid;

    protected $table = 'daftar_dokumen_spp_ls';
}
