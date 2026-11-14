<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecuperarPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RecuperarPasswordController extends Controller
{
    /** Muestra el formulario de recuperación (email + cédula) */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /** Valida email + cédula y permite cambiar la contraseña */
    public function verificar(RecuperarPasswordRequest $request)
    {
        $user = User::where('email', $request->email)
                    ->where('cedula', $request->cedula)
                    ->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'El correo y la cédula no coinciden con ningún usuario.']);
        }

        // Guardamos el id del usuario en sesión temporalmente
        session(['reset_user_id' => $user->id]);

        return redirect()->route('password.reset.form')
            ->with('success', 'Identidad verificada. Ahora puedes cambiar tu contraseña.');
    }

    public function showReset()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.request')
                ->with('error', 'Primero debes verificar tu correo y cédula.');
        }
        return view('auth.reset-password');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $userId = session('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')
                ->with('error', 'Sesión de recuperación expirada.');
        }

        $user = User::findOrFail($userId);
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget('reset_user_id');

        return redirect()->route('login')
            ->with('success', 'Contraseña actualizada. Ya puedes iniciar sesión.');
    }
}