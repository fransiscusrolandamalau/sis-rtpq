 @extends('layouts.admin')
@section('heading', 'Data Mapel')
@section('page')
    <li class="breadcrumb-item active">Data Mapel</li>
@endsection
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
                            <button type="button" class="btn btn-icon btn-outline-primary btn-sm" data-toggle="modal" data-target=".tambah-mapel">
                                <i class="flaticon-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-checkable datatable" style="margin-top: 13px !important">
                    <thead class="text-uppercase">
                        <tr>
                            <th>No.</th>
                            <th>Kelompok</th>
                            <th>Nama Mapel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapel as $result => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->kelompok->nama }}</td>
                            <td>{{ $data->nama_mapel }}</td>
                            <td>
                                <form class="delete_form" action="{{ route('mapel.destroy', $data->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <a href="{{ route('mapel.edit', Crypt::encrypt($data->id)) }}" class="btn btn-icon btn-outline-success btn-sm"><i class="flaticon-edit"></i></a>
                                    <button class="btn btn-icon btn-outline-danger btn-sm"><i class="flaticon-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-md tambah-mapel" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Mapel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mapel.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_mapel">Nama Mapel</label>
                                    <input type="text" id="nama_mapel" name="nama_mapel"
                                        class="form-control @error('nama_mapel') is-invalid @enderror"
                                        placeholder="{{ __('Nama Mata Pelajaran') }}">
                                </div>
                                <div class="form-group">
                                    <label for="kelompok_id">Kelompok</label>
                                    <select id="kelompok_id" name="kelompok_id"
                                        class="form-control @error('kelompok_id') is-invalid @enderror  ">
                                        <option value="">-- Pilih Kelompok Mapel --</option>
                                        @foreach ($kelompok as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="urutan">Nomor Urut</label>
                                    <input type="number" id="urutan" name="urutan"
                                        class="form-control @error('urutan') is-invalid @enderror"
                                        placeholder="{{ __('Nomor Urut') }}">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span><i
                                class="flaticon2-left-arrow-1"></i></span>Kembali</button>
                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                        Tambahkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
  <script>
    $("#MasterData").addClass("menu-item-open");
    $("#liMasterData").addClass("menu-item-open");
    $("#DataMapel").addClass("menu-item-open");
  </script>
@endsection
