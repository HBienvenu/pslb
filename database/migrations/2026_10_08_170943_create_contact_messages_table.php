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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone', 30);
            $table->string('email')->nullable();
            $table->string('sujet', 20)->default('autre');   // location / livraison / media / autre
            $table->text('message');
            $table->boolean('est_lu')->default(false);       // suivi dans l'admin
            $table->string('ip_address', 45)->nullable();    // IPv4 ou IPv6
            $table->softDeletes();
            $table->timestamps();

            $table->index(['est_lu', 'created_at']);
            $table->index('sujet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
