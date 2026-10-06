<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Vérifie si le compte est actif
            if (!$user->active) {
                // Déconnexion immédiate si inactif
                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                // Redirection vers page login avec message
                return redirect()->route('login')
                    ->with('error', 'Votre compte est désactivé. Veuillez contacter l’administrateur.');
            }
        }

        return $next($request);
    }
}
