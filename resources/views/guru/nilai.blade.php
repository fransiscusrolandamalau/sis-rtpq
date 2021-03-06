 @extends('layouts.admin')
@section('heading', 'Deskripsi Nilai')
@section('page')
    <li class="breadcrumb-item active">Deskripsi Nilai</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">Deskripsi Nilai</h3>
                </div>
                <form action="{{ route('nilai.store') }}" method="post">
                    <input type="hidden" name="guru_id" value="{{ $guru->id }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            @if ($guru->dsk($guru->id))
                                <input type="hidden" name="id" value="{{ $nilai->id }}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_gur">Nama Guru</label>
                                        <input type="text" id="nama_gur" name="nama_gur" value="{{ $guru->nama_guru }}" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_a">Predikat A</label>
                                        <textarea class="form-control" required name="predikat_a" id="predikat_a" rows="4">{{ $nilai->deskripsi_a }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_c">Predikat C</label>
                                        <textarea class="form-control" required name="predikat_c" id="predikat_c" rows="4">{{ $nilai->deskripsi_c }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kkm">KKM</label>
                                        <input type="text" onkeypress="return inputAngka(event)" maxlength="2" value="{{ $nilai->kkm }}" id="kkm" name="kkm" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_b">Predikat B</label>
                                        <textarea class="form-control" required name="predikat_b" id="predikat_b" rows="4">{{ $nilai->deskripsi_b }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_d">Predikat D</label>
                                        <textarea class="form-control" required name="predikat_d" id="predikat_d" rows="4">{{ $nilai->deskripsi_d }}</textarea>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_gur">Nama Guru</label>
                                        <input type="text" id="nama_gur" name="nama_gur" value="{{ $guru->nama_guru }}" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_a">Predikat A</label>
                                        <textarea class="form-control" required name="predikat_a" id="predikat_a" rows="4">{{ $nilai->deskripsi_a }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_c">Predikat C</label>
                                        <textarea class="form-control" required name="predikat_c" id="predikat_c" rows="4">{{ $nilai->deskripsi_c }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mapel">Mata Pelajaran</label>
                                        <input type="text" id="mapel" name="mapel" value="{{ $guru->mapel->nama_mapel }}" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="kkm">KKM</label>
                                        <input type="text" onkeypress="return inputAngka(event)" maxlength="2" value="{{ $nilai->kkm }}" id="kkm" name="kkm" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_b">Predikat B</label>
                                        <textarea class="form-control" required name="predikat_b" id="predikat_b" rows="4">{{ $nilai->deskripsi_b }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="predikat_d">Predikat D</label>
                                        <textarea class="form-control" required name="predikat_d" id="predikat_d" rows="4">{{ $nilai->deskripsi_d }}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="#" name="kembali" class="btn btn-default" id="back"><span><i class="flaticon2-left-arrow-1"></i></span>Kembali</a> &nbsp;
                        <button name="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Simpan</button>
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
        window.location="{{ url('/') }}";
        });
    });
    $("#liNilaiGuru").addClass("menu-item-open");
    $("#DesGuru").addClass("menu-item-active");
</script>
@endsection
