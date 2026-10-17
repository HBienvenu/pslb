<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategorieVehicule\CategorieVehiculeController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\CouvertureMediatique\CouvertureMediatiqueController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Formation\FormationController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Livraison\LivraisonController;
use App\Http\Controllers\Profil\ProfilController;
use App\Http\Controllers\Utilisateur\UtilisateurController;
use App\Http\Controllers\Vehicule\ReservationController;
use App\Http\Controllers\Vehicule\VehiculeController;
use Illuminate\Support\Facades\Route;

// Page accueil
Route::get('/', [HomeController::class, 'accueil'])->name('accueil');

// Vue et enregistrement d'une reservation coté front
Route::get('/location', [HomeController::class, 'location'])->name('location');
Route::post('/location-de-voiture/reservation', [HomeController::class, 'reserverVehicule'])->middleware('throttle:5,1')->name('location.reservation.store');

// Vue livraison
Route::get('/livraison', [HomeController::class, 'livraison'])->name('livraison');
Route::post('/livraison', [HomeController::class, 'storeLivraison'])->middleware('throttle:5,1')->name('livraison.store');

// Vue et enregistrement d'une demande media coté front
Route::get('/media', [HomeController::class, 'media'])->name('media');
Route::post('/media/reservation', [HomeController::class, 'demandeServiceMedia'])->middleware('throttle:5,1')->name('media.demande.store');

// Vue et enregistrement du volet formation & services digitaux
Route::get('/formation', [HomeController::class, 'formation'])->name('formation');
Route::post('/formation', [HomeController::class, 'storeFormation'])->middleware('throttle:5,1')->name('formation.store');

Route::get('/a-propos', [HomeController::class, 'aPropos'])->name('apropos');

// Vue contact et envoie de contact cote front
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->middleware('throttle:5,1')->name('contact.store');


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

            // Gestion des categorie de vehicule
            Route::resource('categories-vehicule', CategorieVehiculeController::class)->parameters(['categories-vehicule' => 'categorie_vehicule'])->except(['show', 'create', 'edit']);

            // Gestion des vehicules
            Route::resource('vehicules', VehiculeController::class)->except(['show', 'create', 'edit']);

            // Gestion des reservation de vehicule
            Route::resource('reservations', ReservationController::class)->except(['show', 'create', 'edit']);
            Route::patch('reservations/{reservation}/statut', [ReservationController::class, 'updateStatut'])->name('reservations.statut');

            // Gestion des demande pour couverture mediatique
            Route::resource('demandes-couverture', CouvertureMediatiqueController::class)->parameters(['demandes-couverture' => 'demande'])->except(['show', 'create', 'edit']);
            Route::patch('demandes-couverture/{demande}/statut', [CouvertureMediatiqueController::class, 'updateStatut'])->name('demandes-couverture.statut');

            // Gestion des contacts
            Route::resource('contacts', ContactController::class)->except(['show', 'create', 'edit']);
            Route::patch('contacts/{contact}/statut', [ContactController::class, 'updateStatut'])->name('contacts.statut');

            // Gestion des demandes de livraison
            Route::resource('livraisons', LivraisonController::class)->parameters(['livraisons' => 'livraison'])->except(['show', 'create', 'edit']);
            Route::patch('livraisons/{livraison}/statut', [LivraisonController::class, 'updateStatut'])->name('livraisons.statut');

            // Gestion des demandes formation & services digitaux
            Route::resource('demandes-formation', FormationController::class)->parameters(['demandes-formation' => 'demande'])->except(['show', 'create', 'edit']);
            Route::patch('demandes-formation/{demande}/statut', [FormationController::class, 'updateStatut'])->name('demandes-formation.statut');

            // Gestion du profil utilisateur
            Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
            Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');

            // Gestion Utilisateurs
            Route::resource('utilisateur', UtilisateurController::class)->except(['destroy', 'show', 'create', 'edit']);
        });

        // Route pour la deconnexion
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
