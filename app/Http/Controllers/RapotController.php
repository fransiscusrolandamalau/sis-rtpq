<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Rapot;
use App\Models\Sikap;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RapotController extends Controller
{
    public function index()
    {
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $jadwal = Jadwal::where('guru_id', $guru->id)->orderBy('kelas_id')->get();
        $kelas = $jadwal->groupBy('kelas_id');

        return view('guru.rapot.kelas', compact('kelas', 'guru'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.rapot.home', compact('kelas'));
    }

    public function store(Request $request)
    {
        $guru = Guru::findorfail($request->guru_id);
        $cekJadwal = Jadwal::where('guru_id', $guru->id)->where('kelas_id', $request->kelas_id)->count();
        if ($cekJadwal >= 1) {
            Rapot::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'siswa_id' => $request->siswa_id,
                    'kelas_id' => $request->kelas_id,
                    'guru_id' => $request->guru_id,
                    'mapel_id' => $guru->mapel_id,
                    'k_nilai' => $request->nilai,
                    'k_predikat' => $request->predikat,
                    'k_deskripsi' => $request->deskripsi,
                ]
            );

            return response()->json(['success' => 'Nilai rapot siswa berhasil ditambahkan!']);
        } else {
            return response()->json(['error' => 'Maaf guru ini tidak mengajar kelas ini!']);
        }
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $kelas = Kelas::findorfail($id);
        $siswa = Siswa::where('kelas_id', $id)->get();

        return view('guru.rapot.rapot', compact('guru', 'kelas', 'siswa'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::findorfail($id);
        $siswa = Siswa::orderBy('nama_siswa')->where('kelas_id', $id)->get();

        return view('admin.rapot.index', compact('kelas', 'siswa'));
    }

    public function rapot($id)
    {
        $id = Crypt::decrypt($id);
        $siswa = Siswa::findorfail($id);
        $kelas = Kelas::findorfail($siswa->kelas_id);
        $jadwal = Jadwal::orderBy('mapel_id')->where('kelas_id', $kelas->id)->get();
        $mapel = $jadwal->groupBy('mapel_id');

        return view('admin.rapot.show', compact('mapel', 'siswa', 'kelas'));
    }

    public function predikat(Request $request)
    {
        $nilai = Nilai::where('guru_id', $request->id)->first();
        if ($request->nilai > 90) {
            $newForm[] = [
                'predikat' => 'A',
                'deskripsi' => $nilai->deskripsi_a,
            ];
        } elseif ($request->nilai > 80) {
            $newForm[] = [
                'predikat' => 'B',
                'deskripsi' => $nilai->deskripsi_b,
            ];
        } elseif ($request->nilai > 60) {
            $newForm[] = [
                'predikat' => 'C',
                'deskripsi' => $nilai->deskripsi_c,
            ];
        } else {
            $newForm[] = [
                'predikat' => 'D',
                'deskripsi' => $nilai->deskripsi_d,
            ];
        }

        return response()->json($newForm);
    }

    public function siswa()
    {
        $siswa = Siswa::where('no_induk', Auth::user()->no_induk)->first();
        $kelas = Kelas::findorfail($siswa->kelas_id);
        $pai = Mapel::where('nama_mapel', 'Pendidikan Agama dan Budi Pekerti')->first();
        $ppkn = Mapel::where('nama_mapel', 'Pendidikan Pancasila dan Kewarganegaraan')->first();
        if ($pai != null && $ppkn != null) {
            $Spai = Sikap::where('siswa_id', $siswa->id)->where('mapel_id', $pai->id)->first();
            $Sppkn = Sikap::where('siswa_id', $siswa->id)->where('mapel_id', $ppkn->id)->first();
        } else {
            $Spai = '';
            $Sppkn = '';
        }
        $jadwal = Jadwal::where('kelas_id', $kelas->id)->orderBy('mapel_id')->get();
        $mapel = $jadwal->groupBy('mapel_id');

        return view('siswa.rapot', compact('siswa', 'kelas', 'mapel', 'Spai', 'Sppkn'));
    }
}
