@extends('layouts.presensi')

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Tambah Data Izin / Sakit</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')
<div class="row" style="margin-top: 5rem;">
    <div class="col">
        <form action="/presensi/storeizin" method="POST" id="formIzin">
            @csrf
            <div class="col">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <input type="date" class="form-control datepicker" id="tgl_izin" name="tgl_izin" placeholder="Tanggal Izin">
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <select name="status" id="status" class="form-control">
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <textarea name="keterangan" id="keterangan" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <button type="submit" class="btn btn-primary btn-block">
                            <ion-icon name="refresh-outline"></ion-icon>
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
    <script>
        $("#formIzin").submit(function() {
            // Mengambil data
            var tgl_izin = $("#tgl_izin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();

            // Validasi
            if(tgl_izin == ""){
                Swal.fire({
                    title: 'Oops !',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning',
                });
                return false;
            }else if(status == ""){
                Swal.fire({
                    title: 'Oops !',
                    text: 'Status Harus Diisi',
                    icon: 'warning',
                });
                return false;
            }else if(keterangan == ""){
                Swal.fire({
                    title: 'Oops !',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning',
                });
                return false;
            }
        })
    </script>
@endpush