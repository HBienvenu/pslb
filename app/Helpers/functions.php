<?php

use Carbon\Carbon;

// Vérifie si la fonction n'existe pas déjà avant de la déclarer
if (!function_exists('formatMontantFCFA')) {
    function formatMontantFCFA($montant)
    {
        if (empty($montant)) {
            return '-';
        }
        return number_format($montant, 0, ',', ' ') . ' FCFA';
    }
}

// Formatte les dates
if (!function_exists('formatDateFr')) {
    function formatDateFr($date)
    {
        if (empty($date)) {
            return '-';
        }

        try {
            return Carbon::parse($date)->translatedFormat('d F Y');
        } catch (\Exception $e) {
            return '-';
        }
    }
}

// Prend les initials d'un nom ou d'un nom composé
function getInitiales(string $nom): string
{
    // Nettoyage des espaces inutiles
    $nom = trim($nom);

    // Découpage par espace, tiret ou apostrophe
    $mots = preg_split('/[\s\-\'’]+/u', $nom);

    $initiales = '';

    foreach ($mots as $mot) {
        if (!empty($mot)) {
            // Première lettre en majuscule (UTF-8 safe)
            $initiales .= mb_strtoupper(mb_substr($mot, 0, 1));
        }
    }

    return $initiales;
}

// Faire commencer par une lettre majuscule
function ucfirstUtf8(string $text): string
{
    $text = mb_strtolower($text); // optionnel
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

// Prend les premieres lettres du premier et du dernier mot
if (! function_exists('getInitialesStartEnd')) {
    function getInitialesStartEnd(string $expression): string
    {
        $mots = array_filter(explode(' ', trim($expression)));

        if (empty($mots)) {
            return '?';
        }

        $mots   = array_values($mots);
        $first  = mb_strtoupper(mb_substr($mots[0], 0, 1));
        $last   = count($mots) > 1
            ? mb_strtoupper(mb_substr(end($mots), 0, 1))
            : '';

        return $first . $last;
    }
}

// Genere aleatoirement un mot de 10 caractere
if (! function_exists('generatePassword')) {
    function generatePassword(int $length = 10): string
    {
        $chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        return substr(
            str_shuffle(str_repeat($chars, (int) ceil($length / strlen($chars)))),
            0,
            $length
        );
    }
}

// Convertir au nombre multiple de 10 suivant
function roundUpToNextTen(int $number): int
{
    return (int) (ceil($number / 10) * 10);
}
