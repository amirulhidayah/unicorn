<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\Profil;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AkunLainnyaController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.pages.masterData.akunLainnya.index');
    }

    public function create()
    {
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('created_at', 'asc')->get();
        return view('dashboard.pages.masterData.akunLainnya.create', compact(['daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => ['required', Rule::unique('users')->withoutTrashed()],
                'password' => 'required|min:6',
                'role' => 'required',
                'nama' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'nomor_hp' => 'required',
                'nip' => 'nullable',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'tanda_tangan' => 'required|image|mimes:png',
                'aktif' => 'required',
            ],
            [
                'username.required' => 'Username tidak boleh kosong',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter',
                'role.required' => 'Role tidak boleh kosong',
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
                'tanda_tangan.mimes' => 'Tanda Tangan harus berupa PDF',
                'tanda_tangan.max' => 'Tanda Tangan tidak boleh lebih dari 1 MB',
                'aktif.required' => 'Aktif tidak boleh kosong',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $namaFoto = time() . '.' . $request->foto->extension();
        $namaTandaTangan = time() . '.' . $request->tanda_tangan->extension();

        try {
            DB::transaction(function () use ($request, $namaFoto, $namaTandaTangan) {
                $request->foto->storeAs('profil', $namaFoto);
                $request->tanda_tangan->storeAs('tanda_tangan', $namaTandaTangan);

                if ($request->aktif == 1) {
                    $user = User::where('role', $request->role)->get();
                    if (count($user) > 0) {
                        foreach ($user as $item) {
                            $item->is_aktif = 0;
                            $item->save();
                        }
                    }
                }

                $user = new User();
                $user->username = $request->username;
                $user->password = bcrypt($request->password);
                $user->role = $request->role;
                $user->is_aktif = $request->aktif;
                $user->save();

                $profil = new Profil();
                $profil->user_id = $user->id;
                $profil->nama = $request->nama;
                $profil->jenis_kelamin = $request->jenis_kelamin;
                $profil->alamat = $request->alamat;
                $profil->nomor_hp = $request->nomor_hp;
                $profil->nip = $request->nip;
                $profil->sekretariat_daerah_id = null;
                $profil->foto = $namaFoto;
                $profil->tanda_tangan = $namaTandaTangan;
                $profil->save();
            });
        } catch (QueryException $error) {
            if (Storage::exists('profil/' . $namaFoto)) {
                Storage::delete('profil/' . $namaFoto);
            }

            if (Storage::exists('tanda_tangan/' . $namaTandaTangan)) {
                Storage::delete('tanda_tangan/' . $namaTandaTangan);
            }
            return throw new Exception($error);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function edit(User $user)
    {
        return view('dashboard.pages.masterData.akunLainnya.edit', compact(['user']));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => ['required',  Rule::unique('users')->ignore($user->id)->withoutTrashed()],
                'password' => $request->password ? 'required|min:6' : 'nullable',
                'role' => 'required',
                'nama' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'nomor_hp' => 'required',
                'nip' => 'nullable',
                'foto' => $request->foto ? 'required|image|mimes:jpeg,png,jpg,gif,svg' : 'nullable',
                'tanda_tangan' => $request->tanda_tangan ? 'required|image|mimes:png' : 'nullable',
            ],
            [
                'username.required' => 'Username tidak boleh kosong',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter',
                'role.required' => 'Role tidak boleh kosong',
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
                'tanda_tangan.mimes' => 'Tanda Tangan harus berupa PDF',
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
                $user->role = $request->role;
                $user->save();

                $profil = Profil::where('user_id', $user->id)->first();
                $profil->user_id = $user->id;
                $profil->nama = $request->nama;
                $profil->jenis_kelamin = $request->jenis_kelamin;
                $profil->alamat = $request->alamat;
                $profil->nomor_hp = $request->nomor_hp;
                $profil->nip = $request->nip;
                $profil->sekretariat_daerah_id = null;
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

    public function destroy(User $user)
    {
        try {
            DB::transaction(function () use ($user) {
                $user->delete();
                Profil::where('user_id', $user->id)->delete();
            });
        } catch (QueryException $error) {
            return throw new Exception($error);
        }

        return response()->json(['status' => 'success']);
    }
}
