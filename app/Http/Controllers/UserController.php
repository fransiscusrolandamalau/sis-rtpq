<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        $user = $user->groupBy('role');

        return view('admin.user.index', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
        ]);

        if ($request->role == 'Guru') {
            $countGuru = Guru::where('id_card', $request->nomer)->count();
            $guruId = Guru::where('id_card', $request->nomer)->get();
            foreach ($guruId as $val) {
                $guru = Guru::findorfail($val->id);
            }
            if ($countGuru >= 1) {
                User::create([
                    'name' => $guru->nama_guru,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'id_card' => $request->nomer,
                ]);

                return redirect()->back()->with('success', 'Berhasil menambahkan user Guru baru!');
            } else {
                return redirect()->back()->with('error', 'Maaf User ini tidak terdaftar sebagai guru!');
            }
        } elseif ($request->role == 'Santri') {
            $countsantri = Santri::where('nisn', $request->nomer)->count();
            $santriId = Santri::where('nisn', $request->nomer)->get();
            foreach ($santriId as $val) {
                $santri = Santri::findorfail($val->id);
            }
            if ($countsantri >= 1) {
                User::create([
                    'name' => $santri->nama_santri,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'nisn' => $request->nomer,
                ]);

                return redirect()->back()->with('success', 'Berhasil menambahkan user Santri baru!');
            } else {
                return redirect()->back()->with('error', 'Maaf User ini tidak terdaftar sebagai santri!');
            }
        } else {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan user Admin baru!');
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        if ($id == 'Admin' && Auth::user()->role != 'Admin') {
            return redirect()->back()->with('warning', 'Maaf halaman ini hanya bisa di akses oleh Admin!');
        } else {
            $user = User::where('role', $id)->get();
            $role = $user->groupBy('role');

            return view('admin.user.show', compact('user', 'role'));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::findOrFail($id);

        return view('admin.user.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $user = User::where('id', $id)->first();

        $user_data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
            'tgl_lahir' => $request->tgl_lahir,
        ];

        $user->update($user_data);

        return redirect()->back()->with('success', 'Data user berhasil diperbaharui!');
    }

    public function destroy($id)
    {
        $user = User::findorfail($id);
        if ($user->role == 'Admin') {
            if ($user->id == Auth::user()->id) {
                $user->delete();

                return redirect()->back()->with('warning', 'Data user berhasil dihapus!');
            } else {
                return redirect()->back()->with('error', 'Maaf user ini bukan milik anda!');
            }
        } elseif ($user->role == 'Admin') {
            if ($user->id == Auth::user()->id) {
                $user->delete();

                return redirect()->back()->with('warning', 'Data user berhasil dihapus!');
            } else {
                return redirect()->back()->with('error', 'Maaf user ini bukan milik anda!');
            }
        } else {
            $user->delete();

            return redirect()->back()->with('warning', 'Data user berhasil dihapus!');
        }
    }

    public function email(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $countUser = User::where('email', $request->email)->count();
        if ($countUser >= 1) {
            return redirect()->route('reset.password', Crypt::encrypt($user->id))->with('success', 'Email ini sudah terdaftar!');
        } else {
            return redirect()->back()->with('error', 'Maaf email ini belum terdaftar!');
        }
    }

    public function password($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::findorfail($id);

        return view('auth.passwords.reset', compact('user'));
    }

    public function update_password(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::findorfail($id);
        $user_data = [
            'password' => Hash::make($request->password),
        ];
        $user->update($user_data);

        return redirect()->route('login')->with('success', 'User berhasil diperbarui!');
    }

    public function profile()
    {
        return view('user.pengaturan');
    }

    public function edit_profile()
    {
        $mapel = Mapel::all();
        $kelas = Kelas::all();

        return view('user.profile', compact('mapel', 'kelas'));
    }

    public function ubah_profile(Request $request)
    {
        if ($request->role == 'Guru') {
            $this->validate($request, [
                'nama_guru' => 'required',
                'mapel_id' => 'required',
                'jk' => 'required',
            ]);
            $guru = Guru::where('id_card', Auth::user()->id_card)->first();
            $user = User::where('id_card', Auth::user()->id_card)->first();
            if ($user) {
                $user_data = [
                    'name' => $request->name,
                ];
                $user->update($user_data);
            } else {
            }
            $guru_data = [
                'nama_guru' => $request->name,
                'mapel_id' => $request->mapel_id,
                'jk' => $request->jk,
                'telp' => $request->telp,
                'tmp_lahir' => $request->tmp_lahir,
                'tgl_lahir' => $request->tgl_lahir,
            ];
            $guru->update($guru_data);

            return redirect()->route('profile')->with('success', 'Profile anda berhasil diperbarui!');
        } elseif ($request->role == 'Santri') {
            $this->validate($request, [
                'nama_santri' => 'required',
                'jk' => 'required',
                'kelas_id' => 'required',
            ]);
            $santri = Santri::where('nisn', Auth::user()->nisn)->first();
            $user = User::where('nisn', Auth::user()->nisn)->first();
            if ($user) {
                $user_data = [
                    'name' => $request->name,
                ];
                $user->update($user_data);
            } else {
            }
            $santri_data = [
                'nama_santri' => $request->name,
                'jk' => $request->jk,
                'kelas_id' => $request->kelas_id,
                'telp' => $request->telp,
                'tmp_lahir' => $request->tmp_lahir,
                'tgl_lahir' => $request->tgl_lahir,
            ];
            $santri->update($santri_data);

            return redirect()->route('profile')->with('success', 'Profile anda berhasil diperbarui!');
        } else {
            $user = User::findorfail(Auth::user()->id);
            $data_user = [
                'name' => $request->name,
            ];
            $user->update($data_user);

            return redirect()->route('profile')->with('success', 'Profile anda berhasil diperbarui!');
        }
    }

    public function edit_foto()
    {
        if (Auth::user()->role == 'Guru' || Auth::user()->role == 'Santri') {
            return view('user.foto');
        } else {
            return redirect()->back()->with('error', 'Not Found 404!');
        }
    }

    public function ubah_foto(Request $request)
    {
        if ($request->role == 'Guru') {
            $this->validate($request, [
                'foto' => 'required',
            ]);
            $guru = Guru::where('id_card', Auth::user()->id_card)->first();
            $foto = $request->foto;
            $new_foto = date('s'.'i'.'H'.'d'.'m'.'Y').'_'.$foto->getClientOriginalName();
            $guru_data = [
                'foto' => 'uploads/guru/'.$new_foto,
            ];
            $foto->move('uploads/guru/', $new_foto);
            $guru->update($guru_data);

            return redirect()->route('profile')->with('success', 'Foto Profile anda berhasil diperbarui!');
        } else {
            $this->validate($request, [
                'foto' => 'required',
            ]);
            $santri = Santri::where('nisn', Auth::user()->nisn)->first();
            $foto = $request->foto;
            $new_foto = date('s'.'i'.'H'.'d'.'m'.'Y').'_'.$foto->getClientOriginalName();
            $santri_data = [
                'foto' => 'uploads/santri/'.$new_foto,
            ];
            $foto->move('uploads/santri/', $new_foto);
            $santri->update($santri_data);

            return redirect()->route('profile')->with('success', 'Foto Profile anda berhasil diperbarui!!');
        }
    }

    public function edit_email()
    {
        return view('user.email');
    }

    public function ubah_email(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
        ]);
        $user = User::findorfail(Auth::user()->id);
        $cekUser = User::where('email', $request->email)->count();
        if ($cekUser >= 1) {
            return redirect()->back()->with('error', 'Maaf email ini sudah terdaftar!');
        } else {
            $user_email = [
                'email' => $request->email,
            ];
            $user->update($user_email);

            return redirect()->back()->with('success', 'Email anda berhasil diperbarui!');
        }
    }

    public function edit_password()
    {
        return view('user.password');
    }

    public function ubah_password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::findorfail(Auth::user()->id);
        if ($request->password_lama) {
            if (Hash::check($request->password_lama, $user->password)) {
                if ($request->password_lama == $request->password) {
                    return redirect()->back()->with('error', 'Maaf password yang anda masukkan sama!');
                } else {
                    $user_password = [
                        'password' => Hash::make($request->password),
                    ];
                    $user->update($user_password);

                    return redirect()->back()->with('success', 'Password anda berhasil diperbarui!');
                }
            } else {
                return redirect()->back()->with('error', 'Tolong masukkan password lama anda dengan benar!');
            }
        } else {
            return redirect()->back()->with('error', 'Tolong masukkan password lama anda terlebih dahulu!');
        }
    }

    public function cek_email(Request $request)
    {
        $countUser = User::where('email', $request->email)->count();
        if ($countUser >= 1) {
            return response()->json(['success' => 'Email Anda Benar']);
        } else {
            return response()->json(['error' => 'Maaf user not found!']);
        }
    }

    public function cek_password(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $countUser = User::where('email', $request->email)->count();
        if ($countUser >= 1) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json(['success' => 'Password Anda Benar']);
            } else {
                return response()->json(['error' => 'Maaf user not found!']);
            }
        } else {
            return response()->json(['warning' => 'Maaf user not found!']);
        }
    }
}
