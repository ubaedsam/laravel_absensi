@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->

<style>
    .webcam-capture, .webcam-capture video{
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }

    #map { height: 200px; }
</style>

{{--  Library untuk melihat dimana lokasi object  --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

@endsection
@section('content')
<!-- App Capsule -->
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if($cek > 0)
            <button id="takeabsen" class="btn btn-danger btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Pulang
            </button>
            @else
            <button id="takeabsen" class="btn btn-primary btn-block">
                <ion-icon name="camera-outline"></ion-icon>
                Absen Masuk
            </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="" id="map"></div>
        </div>
    </div>

    <audio id="notifikasi_in">
        <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_out">
        <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="radius_sound">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
<!-- * App Capsule -->
@endsection

@push('myscript')
    <script>
        // Untuk backsound absen masuk dan keluar
        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');
        var radius_sound = document.getElementById('radius_sound');

        // untuk setting ukuran dan kualitas web cameranya
        Webcam.set({
            height:480,
            width:640,
            image_format:'jpeg',
            jpeg_quality: 80
        });

        // untuk menankap hasil setting ke dalam class tag html yang bernama webcam-capture
        Webcam.attach('.webcam-capture')

        // untuk mengatur posisi jarak camera
        var lokasi = document.getElementById('lokasi');
        if(navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        // ketika berhasil mengirim lokasi koordinat posisi object
        function successCallback(position){
            // Untuk mengukur lokasi posisi koordinat object
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;

            // untuk mengkonfigurasi posisi koordinat object pada peta
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Untuk membuat titik marker object di peta
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

            // untuk membatasi jarak absensi di kawasan kantor
            var circle = L.circle([-6.326364856223017, 106.42329100691224], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 20 // untuk mengatur jarak radius absen dari kantor
            }).addTo(map);
        }

        function errorCallback(){

        }

        // Ajax simpan data
        $("#takeabsen").click(function(e){
            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $("#lokasi").val();
            $.ajax({
                type:'POST',
                url:'/presensi/store',
                data:{
                    _token:"{{ csrf_token() }}",
                    image:image,
                    lokasi:lokasi
                },
                cache:false,
                success:function(respond){
                    var status = respond.split("|");
                    if(status[0] == "success"){
                        if (status[2] == "in"){
                            notifikasi_in.play();
                        }else{
                            notifikasi_out.play();
                        }
                        Swal.fire({
                            title: 'Berhasil',
                            text: status[1],
                            icon: 'success'
                          })
                          setTimeout("location.href='/dashboard'", 3000);
                    }else{
                        if (status[2] == "radius"){
                            radius_sound.play();
                        }
                        Swal.fire({
                            title: 'Error',
                            text: status[1],
                            icon: 'error'
                          })
                    }
                }
            });

        });

    </script>
@endpush