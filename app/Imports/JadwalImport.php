<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Maatwebsite\Excel\Concerns\ToModel;

class JadwalImport implements ToModel
{
    public function model(array $row)
    {
        $hari = Hari::where('nama_hari', $row[0])->first();
        $kelas = Kelas::where('nama_kelas', $row[1])->first();
        $mapel = Mapel::where('nama_mapel', $row[2])->first();
        $guru = Guru::where('nama_guru', $row[3])->first();

        return new Jadwal([
            'hari_id' => $hari->id,
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'jam_mulai' => $row[4],
            'jam_selesai' => $row[5],
        ]);
    }
}
