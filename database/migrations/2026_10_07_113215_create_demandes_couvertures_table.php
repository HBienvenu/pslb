<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes_couvertures', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();   // ex : MED-2026-0042

            // Demandeur
            $table->string('nom');
            $table->string('telephone', 30);
            $table->string('email')->nullable();
            $table->string('organisation')->nullable();           // entreprise, association, église…

            // Besoin
            $table->string('titre_evenement');
            $table->string('lieu')->nullable();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();                 // si l'événement dure plusieurs jours
            $table->json('services');                             // ["photo","video","interview","podcast"]
            $table->text('message')->nullable();

            // Suivi interne
            $table->string('statut', 20)->default('en_attente');  // en_attente|confirmee|refusee|annulee|terminee
            $table->unsignedInteger('montant')->nullable();       // devis en FCFA
            $table->text('note_interne')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->index(['statut', 'date_debut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_couvertures');
    }
};
