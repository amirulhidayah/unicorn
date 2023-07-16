<?php

namespace App\Http\Controllers\dashboard\masterData;

use App\Http\Controllers\Controller;
use App\Models\SekretariatDaerah;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('created_at', 'desc')->whereIn('role', ['Bendahara Pengeluaran', 'Bendahara Pengeluaran Pembantu', 'Bendahara Pengeluaran Pembantu Belanja Hibah'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->profil->nama;
                })
                ->addColumn('sekretariat_daerah', function ($row) {
                    return $row->profil->SekretariatDaerah->nama ?? '-';
                })
                ->addColumn('role', function ($row) {
                    return $row->role;
                })
                ->addColumn('foto', function ($row) {
                    return '<img src="' . Storage::url('profil/' . $row->profil->foto) . '" class="img-fluid" width="80px" alt="Responsive image">';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a class="btn btn-warning btn-sm mr-1" href="' . url('/master-data/akun/' . $row->id . '/edit') . '" ><i class="fas fa-edit"></i> Ubah</a><button id="btn-delete" class="btn btn-danger btn-sm mr-1" value="' . $row->id . '" > <i class="fas fa-trash-alt"></i> Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }
        return view('dashboard.pages.masterData.akun.index');
    }

    public function create()
    {
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('created_at', 'asc')->get();
        return view('dashboard.pages.masterData.akun.create', compact(['daftarSekretariatDaerah']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['email', 'required', Rule::unique('users')->withoutTrashed()],
                'password' => 'required|min:6',
                'role' => 'required',
                'nama' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'nomor_hp' => 'required',
                'nip' => 'nullable',
                'sekretariat_daerah' => 'required',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'tanda_tangan' => 'required|image|mimes:png|max:1024',
            ],
            [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter',
                'role.required' => 'Role tidak boleh kosong',
                'nama.required' => 'Nama tidak boleh kosong',
                'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'nomor_hp.required' => 'Nomor HP tidak boleh kosong',
                'sekretariat_daerah.required' => 'Biro organisasi tidak boleh kosong',
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

        $namaFoto = time() . '.' . $request->foto->extension();
        $request->foto->storeAs('profil', $namaFoto);

        $namaTandaTangan = time() . '.' . $request->tanda_tangan->extension();
        $request->tanda_tangan->storeAs('tanda_tangan', $namaTandaTangan);

        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->save();

        $profil = new Profil();
        $profil->user_id = $user->id;
        $profil->nama = $request->nama;
        $profil->jenis_kelamin = $request->jenis_kelamin;
        $profil->alamat = $request->alamat;
        $profil->nomor_hp = $request->nomor_hp;
        $profil->nip = $request->nip;
        $profil->sekretariat_daerah_id = $request->sekretariat_daerah;
        $profil->foto = $namaFoto;
        $profil->tanda_tangan = $namaTandaTangan;
        $profil->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        $daftarSekretariatDaerah = SekretariatDaerah::orderBy('created_at', 'asc')->get();
        return view('dashboard.pages.masterData.akun.edit', compact(['user', 'daftarSekretariatDaerah']));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)->withoutTrashed()],
                'password' => $request->password ? 'required|min:6' : 'nullable',
                'role' => 'required',
                'nama' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'nomor_hp' => 'required',
                'nip' => 'nullable',
                'sekretariat_daerah' => 'required',
                'foto' => $request->foto ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024' : 'nullable',
                'tanda_tangan' => $request->tanda_tangan ? 'required|image|mimes:png|max:1024' : 'nullable',
            ],
            [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter',
                'role.required' => 'Role tidak boleh kosong',
                'nama.required' => 'Nama tidak boleh kosong',
                'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'nomor_hp.required' => 'Nomor HP tidak boleh kosong',
                'sekretariat_daerah.required' => 'Biro organisasi tidak boleh kosong',
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

        if ($request->foto) {
            if (Storage::exists('profil/' . $user->profil->foto)) {
                Storage::delete('profil/' . $user->profil->foto);
            }

            $namaFoto = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('profil', $namaFoto);
        }

        if ($request->tanda_tangan) {
            if (Storage::exists('tanda_tangan/' . $user->profil->tanda_tangan)) {
                Storage::delete('tanda_tangan/' . $user->profil->tanda_tangan);
            }

            $namaTandaTangan = time() . '.' . $request->tanda_tangan->extension();
            $request->tanda_tangan->storeAs('tanda_tangan', $namaTandaTangan);
        }

        $user->email = $request->email;
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
        $profil->sekretariat_daerah_id = $request->sekretariat_daerah;
        if ($request->foto) {
            $profil->foto = $namaFoto;
        }

        if ($request->tanda_tangan) {
            $profil->tanda_tangan = $namaTandaTangan;
        }

        $profil->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        Profil::where('user_id', $user->id)->delete();

        return response()->json(['status' => 'success']);
    }
}
