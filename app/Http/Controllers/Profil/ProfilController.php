<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        return view('admin.profil.profil', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute est invalide.',
            'max' => 'Le champ :attribute est trop long (:max caractères maximum).',
            'email' => 'L’adresse e-mail est invalide.',
            'min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ], [
            'name' => 'nom',
            'email' => 'e-mail',
            'current_password' => 'mot de passe actuel',
            'password' => 'nouveau mot de passe',
        ]);

        if ($request->filled('password') && !Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ])->withInput();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.profil.index')
            ->with('success', 'Votre profil a été mis à jour.');
    }
}
