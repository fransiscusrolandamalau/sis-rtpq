<?php

namespace App\Http\Controllers;

use App\Exports\santriExport;
use App\Imports\santriImport;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\SPP;
use App\Models\Ulangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class SantriController extends Controller
{
    public function index()
    {
        $kelas = Kelas::OrderBy('id', 'asc')->get();

        return view('admin.santri.index', compact('kelas'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nisn' => 'required|string|unique:santri',
            'nama_santri' => 'required',
            'jk' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama' => 'required',
        ]);

        if ($request->foto) {
            $foto = $request->foto;
            $new_foto = date('siHdmY').'_'.$foto->getClientOriginalName();
            $foto->move('uploads/santri/', $new_foto);
            $nameFoto = 'uploads/santri/'.$new_foto;
        } else {
            if ($request->jk == 'L') {
                $nameFoto = 'uploads/santri/32421817012022_default-avatar.png';
            } else {
                $nameFoto = 'uploads/santri/32421817012022_default-avatar.png';
            }
        }

        Santri::create([
            'nisn' => $request->nisn,
            'nama_santri' => $request->nama_santri,
            'jk' => $request->jk,
            'kelas_id' => $request->kelas_id,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'agama' => $request->agama,
            'anak_ke' => $request->anak_ke,
            'status_keluarga' => $request->status_keluarga,
            'alamat_santri' => $request->alamat_santri,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'alamat_ayah' => $request->alamat_ayah,
            'alamat_ibu' => $request->alamat_ibu,
            'nama_wali' => $request->nama_wali,
            'alamat_wali' => $request->alamat_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'tahun_ajaran' => $request->tahun_ajaran,
            'foto' => $nameFoto,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data santri baru!');
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $santri = Santri::findorfail($id);
        $kelas = Kelas::all();

        return view('admin.santri.details', compact('santri', 'kelas'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $santri = Santri::findorfail($id);
        $kelas = Kelas::all();

        return view('admin.santri.edit', compact('santri', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_santri' => 'required',
            'jk' => 'required',
            'kelas_id' => 'required',
        ]);

        $santri = Santri::findorfail($id);
        $user = User::where('nisn', $santri->nisn)->first();
        if ($user) {
            $user_data = [
                'name' => $request->nama_santri,
            ];
            $user->update($user_data);
        } else {
        }
        $santri_data = [
            'nama_santri' => $request->nama_santri,
            'jk' => $request->jk,
            'kelas_id' => $request->kelas_id,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'agama' => $request->agama,
            'anak_ke' => $request->anak_ke,
            'status_keluarga' => $request->status_keluarga,
            'alamat_santri' => $request->alamat_santri,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'alamat_ayah' => $request->alamat_ayah,
            'alamat_ibu' => $request->alamat_ibu,
            'nama_wali' => $request->nama_wali,
            'alamat_wali' => $request->alamat_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'tahun_ajaran' => $request->tahun_ajaran,
        ];
        $santri->update($santri_data);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $santri = Santri::findorfail($id);
        $countUser = User::where('nisn', $santri->nisn)->count();
        if ($countUser >= 1) {
            $user = User::where('nisn', $santri->nisn)->first();
            $santri->delete();
            $user->delete();

            return redirect()->back()->with('warning', 'Data santri berhasil dihapus!');
        } else {
            $santri->delete();

            return redirect()->back()->with('warning', 'Data santri berhasil dihapus!');
        }
    }

    public function ubah_foto($id)
    {
        $id = Crypt::decrypt($id);
        $santri = Santri::findorfail($id);

        return view('admin.santri.ubah-foto', compact('santri'));
    }

    public function naik_kelas($id)
    {
        $id = Crypt::decrypt($id);
        $santri = Santri::findorfail($id);

        if ($santri->kelas_id == 1) {
            $santri->update(['kelas_id' => 2]);
        } elseif ($santri->kelas_id == 2) {
            $santri->update(['kelas_id' => 3]);
        } else {
            return redirect()->back()->with('success', 'Saat ini santri berada dikelas tertinggi.');
        }

        return redirect()->back()->with('success', 'Santri Berhasil Naik Kelas');
    }

    public function update_foto(Request $request, $id)
    {
        $this->validate($request, [
            'foto' => 'required',
        ]);

        $santri = Santri::findorfail($id);
        $foto = $request->foto;
        $new_foto = date('s'.'i'.'H'.'d'.'m'.'Y').'_'.$foto->getClientOriginalName();
        $santri_data = [
            'foto' => 'uploads/santri/'.$new_foto,
        ];
        $foto->move('uploads/santri/', $new_foto);
        $santri->update($santri_data);

        return redirect()->route('santri.index')->with('success', 'Berhasil merubah foto!');
    }

    public function view(Request $request)
    {
        $santri = Santri::OrderBy('nama_santri', 'asc')->where('kelas_id', $request->id)->get();

        foreach ($santri as $val) {
            $newForm[] = [
                'kelas' => $val->kelas->nama_kelas,
                'nisn' => $val->nisn,
                'nama_santri' => $val->nama_santri,
                'jk' => $val->jk,
                'foto' => $val->foto,
            ];
        }

        return response()->json($newForm);
    }

    public function cetak_pdf(Request $request)
    {
        $santri = santri::OrderBy('nama_santri', 'asc')->where('kelas_id', $request->id)->get();
        $kelas = Kelas::findorfail($request->id);

        $pdf = PDF::loadView('santri-pdf', ['santri' => $santri, 'kelas' => $kelas]);

        return $pdf->stream();
    }

    public function kelas($id)
    {
        $id = Crypt::decrypt($id);
        $santri = Santri::where('kelas_id', $id)->OrderBy('nama_santri', 'asc')->get();
        $kelas = Kelas::findorfail($id);

        return view('admin.santri.show', compact('santri', 'kelas'));
    }

    public function export_excel()
    {
        return Excel::download(new santriExport, 'santri.xlsx');
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);
        $file = $request->file('file');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('file_santri', $nama_file);
        Excel::import(new santriImport, public_path('/file_santri/'.$nama_file));

        return redirect()->back()->with('success', 'Data Santri Berhasil Diimport!');
    }

    public function deleteAll()
    {
        $santri = Santri::all();
        if ($santri->count() >= 1) {
            Santri::whereNotNull('id')->delete();
            Santri::withTrashed()->whereNotNull('id')->forceDelete();

            return redirect()->back()->with('success', 'Data table santri berhasil dihapus!');
        } else {
            return redirect()->back()->with('warning', 'Data table santri kosong!');
        }
    }

    public function spp()
    {
        return view('santri.spp.index', [
            'pembayaran' => Pembayaran::where('santri_id', Auth::user()->id)->get(),
            'kelas' => Kelas::OrderBy('nama_kelas', 'asc')->get(),
            'spp' => SPP::OrderBy('tahun', 'asc')->get(),
        ]);
    }

    public function store_spp(Request $request)
    {
        $this->validate($request, [
            'santri_id' => 'required',
            'id_spp' => 'required',
            'bulan_dibayar' => 'required',
            'jenis_pembayaran' => 'required',
            'tgl_bayar' => 'required',
        ]);

        $santri = Santri::where('id', Auth::user()->id)->first();
        $spp = SPP::where('id', $request->id_spp)->first();
        $pembayaran = Pembayaran::orderBy('id', 'DESC')->pluck('id')->first();
        if ($pembayaran == null or $pembayaran == '') {
            $pembayaran = 1;
        } else {
            $pembayaran = $pembayaran + 1;
        }

        $inv = 'INV/'.$spp->tahun.$request->bulan_dibayar.'/00'.$pembayaran;

        if ($request->has('bukti_non_tunai')) {
            $bukti_non_tunai = save_image($request->bukti_non_tunai, 1000, 'uploads/bukti-non-tunai/');
        } else {
            $bukti_non_tunai = $request->bukti_non_tunai;
        }

        Pembayaran::create([
            'kode' => $inv,
            'santri_id' => $request->santri_id,
            'kelas_id' => $santri->kelas_id,
            'id_spp' => $request->id_spp,
            'bulan_dibayar' => $request->bulan_dibayar,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'bukti_non_tunai' => $bukti_non_tunai,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tgl_bayar' => $request->tgl_bayar,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data spp baru!');
    }

    public function GetSantriJenisKelamin()
    {
        $santri = Santri::selectRaw('
            COUNT(CASE WHEN jk = "L" THEN "L" ELSE NULL END) as "Laki-laki",
            COUNT(CASE WHEN jk <> "L" THEN "L" ELSE NULL END) as "Perempuan",
            COUNT(*) as "Total Santri"
        ')->first();

        return response()->json([
            'success' => true,
            'data' => $santri,
        ]);
    }

    public function GetSantriNilaiUAS()
    {
        $nilai = Ulangan::selectRaw('
            COUNT(CASE WHEN uas < 70 AND uas >= 0 THEN "L" ELSE NULL END) as "< 70",
            COUNT(CASE WHEN uas >= 70 AND uas <= 80 THEN "L" ELSE NULL END) as "70 - 80",
            COUNT(CASE WHEN uas >= 81 AND uas <= 90  THEN "L" ELSE NULL END) as "81 - 90",
            COUNT(CASE WHEN uas >= 91 AND uas <= 100  THEN "L" ELSE NULL END) as "91-100",
            COUNT(*) as "Total Santri"
        ')->first();

        return response()->json([
            'success' => true,
            'data' => $nilai,
        ]);
    }
}
