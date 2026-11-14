<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOwnProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Oficina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $oficinas = Oficina::orderBy('nombre')->get();
        return view('perfil.edit', compact('user', 'oficinas'));
    }

    public function update(UpdateOwnProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    // Admin edita el perfil de otro usuario desde aquí también (opcional)
    public function updateUser(UpdateUserRequest $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }
}