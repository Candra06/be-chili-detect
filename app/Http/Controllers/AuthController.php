<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Hasil;
class AuthController extends Controller
{
    public function login()
    {
        if (!Auth::user()) {
            return view("auth.login");
        } else {
            return redirect('/dashboard');
        }
    }

    public function submitLogin(Request $request)
    {
        try {
            $credential =  $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
            $user = null;

            if (Auth::attempt($credential)) {

                return redirect('/');
            };
            $user = User::where("username", $request->email)->first();

            if (isset($user)) {
                return redirect()->back()->with("error", "Maaf, akun Anda sedang tidak aktif")->withInput(request()->all());
            }
            return redirect()->back()->with("error", "Email atau kata sandi salah")->withInput(request()->all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dashboard()
    {
        try {
            $gejala = Gejala::count();
            $penyakit = Penyakit::count();
            $hasil = Hasil::count();

            $data = (object) [
                'gejala' => $gejala,
                'penyakit' => $penyakit,
                'hasil' => $hasil,
            ];
            return view('dashboard', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
