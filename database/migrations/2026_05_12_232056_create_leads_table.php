<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('empresa', 150);
            $table->string('email', 180);
            $table->string('area_atuacao', 120);
            $table->string('origem', 50)->default('evento_empreende_2026');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('consentimento_lgpd')->default(false);
            $table->timestamps();

            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
