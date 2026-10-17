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
        Schema::create('demandes_formation', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->unique();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('service');
            $table->decimal('budget', 12, 2)->nullable();
            $table->date('date_prevue')->nullable();
            $table->text('description')->nullable();
            $table->string('statut')->default('en_attente');
            $table->text('note_interne')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_formation');
    }
};
