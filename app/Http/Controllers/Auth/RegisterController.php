<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Menangani pendaftaran pengguna
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|lowercase', // Pastikan email dalam huruf kecil
            'password' => 'required|min:6|confirmed', // Pastikan ada kolom password_confirmation di form
            'no_telp' => 'required|digits_between:10,15', // Validasi hanya angka 10-15 digit
        ]);

        // Membuat pengguna baru dan meng-hash password
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email), // Simpan email dalam huruf kecil
            'role' => 'user', // Default role
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password), // Hash password
            'remember_token' => Str::random(60), // Generate remember token
        ]);

        Log::info('User registered:', $user->toArray()); // Debugging

        // Login otomatis setelah pendaftaran
        Auth::login($user);

        return redirect()->route('landing')->with('success', 'Account created successfully!');
    }
}
