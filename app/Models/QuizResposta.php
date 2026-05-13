<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResposta extends Model
{
    use HasFactory;

    protected $table = 'quiz_respostas';

    protected $fillable = [
        'lead_id',
        'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9',
        'pontuacao_total',
        'classificacao',
        'cartilha_baixada',
        'cta_clicado',
    ];

    protected function casts(): array
    {
        return [
            'q1' => 'integer',
            'q2' => 'integer',
            'q3' => 'integer',
            'q4' => 'integer',
            'q5' => 'integer',
            'q6' => 'integer',
            'q7' => 'integer',
            'q8' => 'integer',
            'q9' => 'integer',
            'pontuacao_total' => 'integer',
            'cartilha_baixada' => 'boolean',
            'cta_clicado' => 'boolean',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Array com as 9 respostas na ordem q1..q9.
     *
     * @return array<int, int|null>
     */
    public function respostasArray(): array
    {
        return [
            $this->q1, $this->q2, $this->q3,
            $this->q4, $this->q5, $this->q6,
            $this->q7, $this->q8, $this->q9,
        ];
    }
}
