<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
  
    public function index()
    {
     
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('profil', compact('user'));
    }

     public function update(Request $request)
    {
        $user = Auth::user();

        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

 
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? $user->phone_number;
        $user->address = $validated['address'] ?? $user->address;

       
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

   
        $user->save();

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
    }

    
}
