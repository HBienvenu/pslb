<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('site.auths.login');
    }

    // Traitement de la connection a son espace
    public function doLogin(Request $request)
    {
        $email = $request->input('email');
        $key = Str::lower($email) . '|' . $request->ip();

        // Limiter à 5 tentatives toutes les 120 secondes
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('erreur-connexion', "Trop de tentatives. Réessayez dans $seconds secondes.");
        }

        $credentials = [
            'email' => request('email'),
            'password' => request('password'),
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            // Réussite : réinitialiser le compteur
            RateLimiter::clear($key);

            $request->session()->regenerate();
            $request->session()->forget('erreur-connexion');

            return redirect()->intended('/verification-acces');
        }

        // Échec : incrémenter le compteur
        RateLimiter::hit($key, 120); // expire après 120 secondes

        return back()->with('erreur-connexion', 'Identifiants incorrects.')->withInput();
    }

    // Page de verification des accès
    public function verificationAcces()
    {
        return view('admin.authentification.verification_acces', ['user' => Auth::user()]);
    }

    // Vue mot de passe oublié
    public function forgotPassword()
    {
        return view('admin.authentification.forgot_password');
    }

    // Envoie de mail pour le changement de mot de passe oublié
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.exists' => 'Aucun compte trouvé avec cet email.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Formulaire de reinitialisation du mot de passe
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$email || !$record || !Hash::check($token, $record->token) || Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            abort(403, 'Token invalide ou expiré.');
        }
        return view('admin.authentification.change_forgot_password', ['token' => $token]);
    }

    // Le traitement de la reinitialisation du mot de passe
    public function reset(ForgotPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) use ($request) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                // Protection session fixation
                $request->session()->regenerate();
            }
        );

        // Nettoyage strict du token utilisé
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with(['success' => __($status)])
            : back()->withErrors(['email' => [__($status)]]);
    }

    // Deconnexion
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/connexion');
    }
}
