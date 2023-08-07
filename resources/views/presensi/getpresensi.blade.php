@if ($presensi->isEmpty())
    <div class="alert alert-warning text-center">
        Data Absensi Belum Ada
    </div>
@endif

// untuk menghitung jumlah yang terlambat
@php
function selisih($jam_masuk, $jam_keluar)
{
    list($h, $m, $s) = explode(":", $jam_masuk);
    $dtAwal = mktime($h, $m, $s, "1", "1", "1");
    list($h, $m, $s) = explode(":", $jam_keluar);
    $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
    $dtSelisih = $dtAkhir - $dtAwal;
    $totalmenit = $dtSelisih / 60;
    $jam = explode(".", $totalmenit / 60);
    $sisamenit = ($totalmenit / 60) - $jam[0];
    $sisamenit2 = $sisamenit * 60;
    $jml_jam = $jam[0];
    return $jml_jam . ":" . round($sisamenit2);
}
@endphp

@foreach ($presensi as $item)
@php
    $foto_in = Storage::url('uploads/absensi/'.$item->foto_in);
    $foto_out = Storage::url('uploads/absensi/'.$item->foto_out);
@endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->nik }}</td>
        <td>{{ $item->nama_lengkap }}</td>
        <td>{{ $item->nama_department }}</td>
        <td>{{ $item->jam_in }}</td>
        <td>
            <img src="{{ url($foto_in) }}" class="avatar" alt="">
        </td>
        <td>
            <span class="badge bg-danger">{!! $item->jam_out != null ? $item->jam_out : 'Belum Absen' !!}</span>
        </td>
        <td>
            @if ($item->jam_out != null)
            <img src="{{ url($foto_out) }}" class="avatar" alt="">
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hourglass-high" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M6.5 7h11"></path>
                <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z"></path>
                <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z"></path>
            </svg>
            @endif
        </td>
        <td>
            @if ($item->jam_in >= '07:00')
            @php
                $jamterlambat = selisih('07:00:00',$item->jam_in);
            @endphp
                <span class="badge bg-danger">Terlambat {{ $jamterlambat }}</span>
            @else
                <span class="badge bg-success">Tepat Waktu</span>
            @endif
        </td>
    </tr>
@endforeach