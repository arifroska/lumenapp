<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        
        $user = User::where('username', $username)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Username/Password salah'], 401);
        }

        $user->update([
            'token' => bin2hex(random_bytes(40))
        ]);

        return response()->json($user);
    }

    public function logout(Request $request){
        
        User::whereToken(auth()->guard('api')->user()->token)->update([
            'token' => null
        ]);

        return response()->json(['message' => 'Pengguna telah logout']);
    }

    public function register(Request $request){
        $username = $request->username;
        $password = Hash::make($request->password);
        $email = $request->email;
        $no_hp = $request->no_hp;
        $nama = $request->nama;
    
        User::create([
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'no_hp' => $no_hp,
            'nama' => $nama
        ]);

        return response()->json(['message' => 'pendaftaran berhasil']);
    }

}
