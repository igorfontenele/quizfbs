<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'telefone',
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

    /** Apenas os dígitos do telefone (para montar links wa.me). */
    protected function telefoneDigits(): Attribute
    {
        return Attribute::get(fn () => preg_replace('/\D+/', '', (string) $this->telefone));
    }

    /** Link de WhatsApp para o número do lead, ou null se não houver número válido. */
    public function whatsappUrl(): ?string
    {
        $digits = $this->telefone_digits;

        if (strlen($digits) < 10) {
            return null;
        }

        // Garante o DDI 55 (Brasil) se o número veio só com DDD + número.
        if (! str_starts_with($digits, '55') || strlen($digits) <= 11) {
            $digits = '55'.$digits;
        }

        return "https://wa.me/{$digits}";
    }

    public function quizResposta(): HasOne
    {
        return $this->hasOne(QuizResposta::class);
    }
}
