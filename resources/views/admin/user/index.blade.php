 @extends('layouts.admin')
@section('heading', 'Data User')
@section('page')
    <li class="breadcrumb-item active">Data User</li>
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
                            <button type="button" class="btn btn-icon btn-outline-primary btn-sm" data-toggle="modal" data-target=".tambah-user">
                                <i class="flaticon-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover table-checkable datatable" style="margin-top: 13px !important">
                        <thead class="text-uppercase">
                            <tr>
                                <th>Level User</th>
                                <th>Jumlah User</th>
                                <th>Lihat User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $role => $data)
                            <tr>
                                <td>{{ $role }}</td>
                                <td>{{ $data->count() }}</td>
                                <td>
                                    <a href="{{ route('user.show', Crypt::encrypt($role)) }}" class="btn btn-icon btn-outline-success btn-sm">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-md tambah-user" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">E-Mail Address</label>
                                    <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="role">Level User</label>
                                    <select id="role" type="text"
                                        class="form-control @error('role') is-invalid @enderror  " name="role"
                                        value="{{ old('role') }}" autocomplete="role">
                                        <option value="">-- Select {{ __('Level User') }} --</option>
                                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Guru">Guru</option>
                                        <option value="Santri">Santri</option>
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group" id="noId">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" placeholder="{{ __('Password') }}"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password" placeholder="{{ __('Confirm Password') }}"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password_confirmation" autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span><i class="flaticon2-left-arrow-1"></i></span>Kembali</button>
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
    $(document).ready(function(){
        $('#role').change(function(){
            var kel = $('#role option:selected').val();
            if (kel == "Guru") {
              $("#noId").html('<label for="nomer">Nomer Id Card</label><input id="nomer" type="text" maxlength="5" onkeypress="return inputAngka(event)" placeholder="No Id Card" class="form-control" name="nomer" autocomplete="off">');
            } else if(kel == "Santri") {
              $("#noId").html(`<label for="nomer">NISN</label><input id="nomer" type="text" placeholder="NISN" class="form-control" name="nomer" autocomplete="off">`);
            } else if(kel == "Admin" || kel == "Kepala Sekolah") {
              $("#noId").html(`<label for="name">Nama Lengkap</label><input id="name" type="text" placeholder="Nama Lengkap" class="form-control" name="name" autocomplete="off">`);
            } else {
              $("#noId").html("")
            }
        });
    });

    $("#MasterData").addClass("menu-item-open");
    $("#liMasterData").addClass("menu-item-open");
    $("#DataUser").addClass("menu-item-open");
  </script>
@endsection
