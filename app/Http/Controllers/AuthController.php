<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Proses Login Karyawan
    public function proseslogin(Request $request)
    {
        if(Auth::guard('karyawan')->attempt(['nik'=>$request->nik,'password'=>$request->password])){
            return redirect('/dashboard');
        }else{
            return redirect('/')->with(['warning' => 'Nik / Password Salah']);
        }

        // $pass = 123;
        // echo Hash::make($pass);
    }

    // Proses Logout Karyawan
    public function proseslogout()
    {
        if(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }

    // Proses Login Admin
    public function prosesloginadmin(Request $request)
    {
        if(Auth::guard('user')->attempt(['email'=>$request->email,'password'=>$request->password])){
            return redirect('/panel/dashboardadmin');
        }else{
            return redirect('/panel')->with(['warning' => 'Email / Password Salah']);
        }
    }

    public function proseslogoutadmin()
    {
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }
}
