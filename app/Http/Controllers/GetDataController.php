<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Spd;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class GetDataController extends Controller
{
    public function spd(Request $request)
    {
        $id = $request->id;
        $tahun = '8fef08db-e1bf-4a1f-8bd2-9809d5e60426';
        $sekretariatDaerah = '68c37c05-84a4-493b-9419-35cb6d10a319';

        try {
            $spd = Spd::where('kegiatan_id', $id)->where('tahun_id', $tahun)->first();
            return response()->json([
                'status' => 'success',
                'data' => $spd
            ]);
        } catch (QueryException $error) {
            return throw new Exception($error);
        }
    }
}
