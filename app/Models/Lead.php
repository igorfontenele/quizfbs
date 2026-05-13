<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'empresa',
        'email',
        'area_atuacao',
        'origem',
        'ip',
        'user_agent',
        'consentimento_lgpd',
    ];

    protected function casts(): array
    {
        return [
            'consentimento_lgpd' => 'boolean',
        ];
    }

    public function quizResposta(): HasOne
    {
        return $this->hasOne(QuizResposta::class);
    }
}
