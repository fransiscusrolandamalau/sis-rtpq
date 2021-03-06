<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Santri;
use App\Models\SPP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('admin.pembayaran.index', [
            'santri' => Santri::all(),
            'kelas' => Kelas::OrderBy('nama_kelas', 'asc')->get(),
            'spp' => SPP::OrderBy('tahun', 'asc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'santri_id' => 'required',
            'id_spp' => 'required',
            'bulan_dibayar' => 'required',
            'jenis_pembayaran' => 'required',
            'tgl_bayar' => 'required',
        ]);

        $santri = Santri::where('id', $request->santri_id)->first();
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

        return redirect()->back()->with('success', 'Berhasil menambahkan data pembayaran baru!');
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $kelas = Kelas::findorfail($id);
        $pembayaran = Pembayaran::where('kelas_id', $id)
                        ->orderBy('bulan_dibayar', 'asc')
                        ->orderBy('santri_id', 'desc')
                        ->get();

        return view('admin.pembayaran.show', compact('pembayaran', 'kelas'));
    }

    public function edit()
    {
        return view('admin.pembayaran.edit');
    }

    public function gagalPembayaran($id)
    {
        $id = Crypt::decrypt($id);
        Pembayaran::where('id', $id)
                        ->update(['status' => 2]);

        return redirect()->back()->with('success', 'Berhasil memperbaharui status!');
    }

    public function berhasilPembayaran($id)
    {
        $id = Crypt::decrypt($id);
        Pembayaran::where('id', $id)
                        ->update(['status' => 1]);

        return redirect()->back()->with('success', 'Berhasil memperbaharui status!');
    }
}
