 @extends('layouts.admin')
@section('heading')
    Jadwal Kelas {{ $kelas->nama_kelas }}
@endsection
@section('page')
    <li class="breadcrumb-item active">Jadwal Kelas</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b">
                <div class="card-header py-3">
                    <div class="card-title">
                        <h3 class="card-label">@yield('heading')</h3>
                    </div>
                </div>
                <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-checkable datatable" style="margin-top: 13px !important">
                    <thead class="text-uppercase">
                        <tr>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Pelajaran</th>
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
                            <td>{{ $data->jam_mulai }} - {{ $data->jam_selesai }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#Jadwalsantri").addClass("menu-item-open");
    </script>
@endsection
