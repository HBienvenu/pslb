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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();   // ex : LOC-2026-0042
            $table->foreignId('vehicule_id')->nullable()
                ->constrained('vehicules')->nullOnDelete();

            // Client
            $table->string('nom');
            $table->string('telephone', 30);
            $table->string('email')->nullable();

            // Demande
            $table->boolean('avec_chauffeur')->default(false);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('lieu_prise')->nullable();
            $table->text('message')->nullable();

            // Suivi interne
            $table->string('statut', 20)->default('en_attente'); // en_attente|confirmee|refusee|annulee|terminee
            $table->text('note_interne')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            $table->index(['statut', 'date_debut']);
            $table->index(['vehicule_id', 'date_debut', 'date_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
