<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();

            foreach (range(1, 9) as $n) {
                $table->unsignedTinyInteger("q{$n}")->nullable();
            }

            $table->unsignedTinyInteger('pontuacao_total')->nullable();
            $table->enum('classificacao', ['verde', 'amarelo', 'vermelho'])->nullable();
            $table->boolean('cartilha_baixada')->default(false);
            $table->boolean('cta_clicado')->default(false);
            $table->timestamps();

            $table->index('classificacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_respostas');
    }
};
