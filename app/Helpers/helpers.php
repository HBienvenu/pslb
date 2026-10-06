<?php

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

// Permet de verifier si l'utilisateur doit forcement changer ou non son mot de passe
if (!function_exists('doitChangerMotDePasse')) {
    function doitChangerMotDePasse($user = null): bool
    {
        // Si aucun utilisateur n'est passé, on prend celui connecté
        $user = $user ?? Auth::user();

        if (!$user) {
            return false;
        }

        return $user->must_change_password;
    }
}

// Retourne un texte avec un url, email
if (! function_exists('autoLink')) {
    function autoLink($text)
    {
        $text = e($text);

        // URLs
        $text = preg_replace(
            '~(https?://[^\s<]+)~i',
            '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">$1</a>',
            $text
        );

        // Emails
        $text = preg_replace(
            '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/',
            '<a href="mailto:$1" class="text-blue-600 hover:underline">$1</a>',
            $text
        );

        return new HtmlString(nl2br($text));
    }
}
