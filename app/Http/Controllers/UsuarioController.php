<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuario');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function edit_card_settings()
    {
        $user = Auth::user();
        return view('profile.configuration', compact('user'));
    }

    public function update_card_settings(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only([
            'card_very_easy_days',
            'card_easy_days',
            'card_medium_days',
            'card_hard_days',
            'card_very_hard_days',
        ]));

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
