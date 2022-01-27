 @extends('layouts.admin')
@section('heading', 'Edit Jadwal')
@section('page')
    <li class="breadcrumb-item active"><a href="{{ route('jadwal.index') }}">Jadwal</a></li>
    <li class="breadcrumb-item active">Edit Jadwal</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Jadwal</h3>
                </div>
                <form action="{{ route('jadwal.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hari_id">Hari</label>
                                    <select id="hari_id" name="hari_id"
                                        class="form-control @error('hari_id') is-invalid @enderror  ">
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach ($hari as $data)
                                        <option value="{{ $data->id }}" @if ($jadwal->hari_id == $data->id)
                                            selected
                                            @endif
                                            >{{ $data->nama_hari }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kelas_id">Kelas</label>
                                    <select id="kelas_id" name="kelas_id"
                                        class="form-control @error('kelas_id') is-invalid @enderror  ">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelas as $data)
                                        <option value="{{ $data->id }}" @if ($jadwal->kelas_id == $data->id)
                                            selected
                                            @endif
                                            >{{ $data->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="guru_id">Pengajar</label>
                                    <select id="guru_id" name="guru_id"
                                        class="form-control @error('guru_id') is-invalid @enderror  ">
                                        <option value="" @if ($jadwal->guru_id)
                                            selected
                                            @endif>-- Pilih Guru --</option>
                                        @foreach ($guru as $data)
                                        <option value="{{ $data->id }}" @if ($jadwal->guru_id == $data->id)
                                            selected
                                            @endif
                                            >{{ $data->nama_guru }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai">Jam Mulai</label>
                                    <input type='time' value="{{ $jadwal->jam_mulai }}" id="jam_mulai" name='jam_mulai'
                                        class="form-control @error('jam_mulai') is-invalid @enderror"
                                        placeholder='JJ:mm:dd'>
                                </div>
                                <div class="form-group">
                                    <label for="jam_selesai">Jam Selesai</label>
                                    <input type='time' value="{{ $jadwal->jam_selesai }}" name='jam_selesai'
                                        class="form-control @error('jam_selesai') is-invalid @enderror"
                                        placeholder='JJ:mm:dd'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="#" name="kembali" class="btn btn-default" id="back"><span><i class="flaticon2-left-arrow-1"></i></span>Kembali</a> &nbsp;

                        <button name="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#back').click(function() {
        window.location="{{ route('jadwal.show', Crypt::encrypt($jadwal->kelas_id)) }}";
        });
    });
    $("#MasterData").addClass("menu-item-open");
    $("#liMasterData").addClass("menu-item-open");
    $("#DataJadwal").addClass("menu-item-open");
</script>
@endsection
