<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {


        $query = Karyawan::query();
        $query->select('karyawan.*','nama_department');
        $query->join('departemen','karyawan.kode_dept','=','departemen.kode_dept');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_lengkap)){
            $query->where('nama_lengkap', 'like' ,'%'. $request->nama_lengkap. '%');
        }
        if(!empty($request->kode_dept)){
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $karyawan = $query->paginate(1);

        // Menampilkan semua data karyawan
        // $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')
        // ->join('departemen','karyawan.kode_dept','=','departemen.kode_dept')
        // ->paginate(1);

        // Menampilkan semua data departemen
        $departemen = DB::table('departemen')->get();

        return view('karyawan.index', compact('karyawan', 'departemen'));
    }
}
