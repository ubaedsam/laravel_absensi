<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Dashboard karyawan
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1; // hasilnya satu bulan ini
        $tahunini = date("Y"); // hasilnya satu tahun sekarang
        
        // Mengambil semua data absen setiap karyawan
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensihariini = DB::table('presensi')->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')->where('nik',$nik)->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')->whereRaw('YEAR(tgl_presensi)="'. $tahunini .'"')->orderBy('tgl_presensi')->get();

        // Menghitung jumlah absensi karyawan apakah hadir, izin, sakit, terlambat
        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "08:00",1,0)) as jmlterlambat')
        ->where('nik',$nik)
        ->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
        ->whereRaw('YEAR(tgl_presensi)="'. $tahunini .'"')
        ->first();
        
        // Menampilkan semua data presensi yang datang hari ini
        $leaderboard = DB::table('presensi')
        ->join('karyawan','presensi.nik', '=', 'karyawan.nik') // join relasi ke tabel karyawan
        ->where('tgl_presensi',$hariini)
        ->orderBy('jam_in')
        ->get();
        $namabulan = ["","Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

        // Menjumlahkan data izin atau sakit
        $rekapizin = DB::table('pengajuan_izin')
        ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin , SUM(IF(status="s",1,0)) as jmlsakit')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_izin)="'.$bulanini.'"')
        ->whereRaw('YEAR(tgl_izin)="'. $tahunini .'"')
        ->where('status_approved', 1)
        ->first();

        return view('dashboard.dashboard', compact('leaderboard','rekappresensi','presensihariini','historibulanini','namabulan','bulanini','tahunini','rekapizin'));
    }

    // Dashboard admin
    public function dashboardadmin()
    {
        return view('dashboard.dashboardadmin');
    }
}
