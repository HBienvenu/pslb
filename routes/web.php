<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieVehicule\CategorieVehiculeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Utilisateur\UtilisateurController;
use App\Http\Controllers\Vehicule\ReservationController;
use App\Http\Controllers\Vehicule\VehiculeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'accueil'])->name('accueil');

Route::get('/location', [HomeController::class, 'location'])->name('location');
Route::post('/location-de-voiture/reservation', [HomeController::class, 'reserver'])->middleware('throttle:5,1')->name('location.reservation.store');

Route::get('/livraison', [HomeController::class, 'livraison'])->name('livraison');

Route::get('/media', [HomeController::class, 'media'])->name('media');

Route::get('/a-propos', [HomeController::class, 'aPropos'])->name('apropos');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');


// Les routes ou il faut pas etre connecté
Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return to_route('login');
    });

    // Vue de connexion admin
    Route::get('admin/connexion', [AuthController::class, 'login'])->name('login');

    // Triatement de la connexion
    Route::post('se-connecter', [AuthController::class, 'doLogin'])->name('login.store');

    // Mot de passe oublié
    Route::get('/mot-de-passe-oublie', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/mot-de-passe-oublie', [AuthController::class, 'sendResetLinkEmail'])->name('send.email.password.forgot');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.renitialise.update');
});


// Les routes demandant l'authentification
Route::middleware('auth')->group(function () {

    Route::get('verification-acces', [AuthController::class, 'verificationAcces'])->name('verification.acces');

    Route::middleware('check.active')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // -------------Toutes mes routes pour la gestion des pages administrations ----------------------  //
        Route::prefix('admin')->name('admin.')->group(function () {

            Route::resource('categories-vehicule', CategorieVehiculeController::class)->parameters(['categories-vehicule' => 'categorie_vehicule'])->except(['show', 'create', 'edit']);

            Route::resource('vehicules', VehiculeController::class)->except(['show', 'create', 'edit']);;
            Route::resource('reservations', ReservationController::class)->except(['show', 'create', 'edit']);

            Route::patch('reservations/{reservation}/statut', [ReservationController::class, 'updateStatut'])
                ->name('reservations.statut');

            // Gestion Utilisateurs
            Route::resource('utilisateur', UtilisateurController::class)->except(['destroy', 'show', 'create', 'edit']);
        });

        // Route pour la deconnexion
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
