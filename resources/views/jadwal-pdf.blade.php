<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistem Informasi Santri Rumah Tahfidz Pejuang Quran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="shrotcut icon" href="{{ asset('img/favicon.ico') }}">
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <table class="table table-striped table-bordered table-hover table-checkable datatable" style="margin-top: 13px !important">
                <thead class="text-uppercase">
                    <tr>
                        <th colspan="5" class="text-center">Jadwal Pelajaran Kelas {{ $kelas->nama_kelas }}</th>
                    </tr>
                    <tr>
                        <th>Hari</th>
                        <th>Jadwal</th>
                        <th>Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal as $data)
                        <tr>
                            <td>{{ $data->hari->nama_hari }}</td>
                            <td>
                                <h5 class="card-title">{{ $data->mapel->nama_mapel }}</h5>
                                <p class="card-text"><small class="text-muted">{{ $data->guru->nama_guru }}</small></p>
                            </td>
                            <td>{{ $data->jam_mulai.' - '.$data->jam_selesai }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
