<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.profil.index');
    }

    public function update(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'username' => ['required', Rule::unique('users')->ignore($user->id)],
                'password' => $request->password ? 'required|min:6' : 'nullable',
                'nama' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'nomor_hp' => 'required',
                'nip' => 'nullable',
                'foto' => $request->foto ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024' : 'nullable',
                'tanda_tangan' => $request->tanda_tangan ? 'required|image|mimes:png|max:1024' : 'nullable',
            ],
            [
                'username.required' => 'Username tidak boleh kosong',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter',
                'nama.required' => 'Nama tidak boleh kosong',
                'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'nomor_hp.required' => 'Nomor HP tidak boleh kosong',
                'foto.required' => 'Foto tidak boleh kosong',
                'foto.image' => 'Foto harus berupa gambar',
                'foto.mimes' => 'Foto harus berupa gambar',
                'foto.max' => 'Foto tidak boleh lebih dari 1 MB',
                'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
                'tanda_tangan.image' => 'Tanda Tangan harus berupa gambar',
                'tanda_tangan.mimes' => 'Tanda Tangan harus berupa gambar',
                'tanda_tangan.max' => 'Tanda Tangan tidak boleh lebih dari 1 MB',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $namaFotoSebelumnya = $user->profil->foto;
        $namaTandaTanganSebelumnya = $user->profil->tanda_tangan;

        $namaFoto = '';
        $namaTandaTangan = '';

        if ($request->foto) {
            $namaFoto = time() . '.' . $request->foto->extension();
        }
        if ($request->tanda_tangan) {
            $namaTandaTangan = time() . '.' . $request->tanda_tangan->extension();
        }

        try {
            DB::transaction(function () use ($request, $namaFoto, $namaTandaTangan, $user) {
                if ($request->foto) {
                    $request->foto->storeAs('profil', $namaFoto);
                }

                if ($request->tanda_tangan) {
                    $request->tanda_tangan->storeAs('tanda_tangan', $namaTandaTangan);
                }

                $user->username = $request->username;
                if ($request->password) {
                    $user->password = bcrypt($request->password);
                }
                $user->save();

                $profil = Profil::where('user_id', $user->id)->first();
                $profil->user_id = $user->id;
                $profil->nama = $request->nama;
                $profil->jenis_kelamin = $request->jenis_kelamin;
                $profil->alamat = $request->alamat;
                $profil->nomor_hp = $request->nomor_hp;
                $profil->nip = $request->nip;

                if ($request->foto) {
                    $profil->foto = $namaFoto;
                }

                if ($request->tanda_tangan) {
                    $profil->tanda_tangan = $namaTandaTangan;
                }

                $profil->save();
            });
        } catch (QueryException $error) {
            if ($request->foto) {
                if (Storage::exists('profil/' . $namaFoto)) {
                    Storage::delete('profil/' . $namaFoto);
                }
            }

            if ($request->tanda_tangan) {
                if (Storage::exists('tanda_tangan/' . $namaTandaTangan)) {
                    Storage::delete('tanda_tangan/' . $namaTandaTangan);
                }
            }
            return throw new Exception($error);
        }

        if ($request->foto) {
            if (Storage::exists('profil/' . $namaFotoSebelumnya)) {
                Storage::delete('profil/' . $namaFotoSebelumnya);
            }
        }

        if ($request->tanda_tangan) {
            if (Storage::exists('tanda_tangan/' . $namaTandaTanganSebelumnya)) {
                Storage::delete('tanda_tangan/' . $namaTandaTanganSebelumnya);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
