<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\QuizResposta;
use App\Services\QuizClassifierService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizResposta>
 */
class QuizRespostaFactory extends Factory
{
    protected $model = QuizResposta::class;

    public function definition(): array
    {
        $valores = collect(range(1, 9))
            ->mapWithKeys(fn ($n) => ["q{$n}" => fake()->numberBetween(0, 2)])
            ->all();

        $total = array_sum($valores);

        return [
            'lead_id' => Lead::factory(),
            ...$valores,
            'pontuacao_total' => $total,
            'classificacao' => app(QuizClassifierService::class)->classify($total)['classificacao'],
            'cartilha_baixada' => false,
            'cta_clicado' => false,
        ];
    }

    /** Força uma pontuação total específica (distribuída entre q1..q9). */
    public function comPontuacao(int $total): static
    {
        $total = max(0, min(QuizClassifierService::PONTUACAO_MAXIMA, $total));

        $valores = array_fill(1, 9, 0);
        $restante = $total;
        for ($n = 1; $n <= 9 && $restante > 0; $n++) {
            $v = min(2, $restante);
            $valores[$n] = $v;
            $restante -= $v;
        }

        return $this->state(function () use ($valores, $total) {
            return [
                ...collect($valores)->mapWithKeys(fn ($v, $n) => ["q{$n}" => $v])->all(),
                'pontuacao_total' => $total,
                'classificacao' => app(QuizClassifierService::class)->classify($total)['classificacao'],
            ];
        });
    }
}
