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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->nullable()
                ->constrained('categorie_vehicules')->nullOnDelete();
            $table->string('nom');
            $table->string('immatriculation')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();                 // chemin dans storage/app/public
            $table->unsignedTinyInteger('nombre_places')->nullable();
            $table->string('boite_vitesse', 20)->nullable();     // Manuelle / Automatique
            $table->string('carburant', 30)->nullable();         // Essence / Diesel / Hybride…
            $table->boolean('climatisation')->default(true);
            $table->unsignedInteger('prix_jour')->nullable();    // en FCFA
            $table->boolean('est_disponible')->default(true);    // visible sur le site
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->softDeletes();
            $table->index(['est_disponible', 'ordre']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
