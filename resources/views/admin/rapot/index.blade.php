 @extends('layouts.admin')
@section('heading', 'Pilih Santri')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b">
            <div class="card-header py-3">
                <div class="card-title">
                    <h3 class="card-label">@yield('heading')</h3>
                </div>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline mr-2">
                        <a href="{{ route('rapot-kelas') }}" class="btn btn-default btn-sm">
                            <span><i class="flaticon2-left-arrow-1"></i></span>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col-md-12">
                    <table id="example1" class="table table-striped table-bordered table-hover table-checkable datatable" style="margin-top: 13px !important">
                    <thead class="text-uppercase">
                        <tr>
                        <th>No.</th>
                        <th>Nama Santri</th>
                        <th>NISN</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @foreach ($santri as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->nama_santri }}</td>
                            <td>{{ $data->nisn }}</td>
                            <td><a href="{{ route('rapot-show', Crypt::encrypt($data->id)) }}" class="btn btn-icon btn-outline-info btn-sm"><i class="flaticon-eye"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#Nilai").addClass("menu-item-open");
        $("#liNilai").addClass("menu-item-open");
        $("#Rapot").addClass("menu-item-open");
    </script>
@endsection
