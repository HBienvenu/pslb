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
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();

            $table->string('nom');
            $table->string('telephone', 30);
            $table->string('email')->nullable();

            $table->string('ville_depart', 50)->nullable();
            $table->text('adresse_depart')->nullable();
            $table->string('ville_arrivee', 50)->nullable();
            $table->text('adresse_livraison')->nullable();

            $table->string('type_colis')->nullable();
            $table->decimal('poids_kg', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->date('date_livraison')->nullable();
            $table->string('heure_livraison')->nullable();
            $table->string('image_colis')->nullable();

            $table->string('statut')->default('en_attente');
            $table->text('note_interne')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['statut', 'date_livraison']);
            $table->index(['ville_depart', 'ville_arrivee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraisons');
    }
};
