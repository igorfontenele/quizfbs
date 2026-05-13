<?php

namespace App\Services;

use InvalidArgumentException;

class QuizClassifierService
{
    public const PONTUACAO_MAXIMA = 18;

    /**
     * Classifica a pontuação total (0–18) em verde / amarelo / vermelho.
     *
     * @return array{
     *     classificacao: string,
     *     cor_hex: string,
     *     flux_variant: string,
     *     titulo: string,
     *     mensagem: string,
     *     cta_texto: string,
     *     cartilha_slug: string,
     *     fechamento: string,
     *     pontuacao_total: int
     * }
     */
    public function classify(int $total): array
    {
        if ($total < 0 || $total > self::PONTUACAO_MAXIMA) {
            throw new InvalidArgumentException(
                "Pontuação inválida: {$total}. Deve estar entre 0 e ".self::PONTUACAO_MAXIMA.'.'
            );
        }

        foreach (config('quiz.classificacoes') as $slug => $faixa) {
            if ($total >= $faixa['min'] && $total <= $faixa['max']) {
                return [
                    'classificacao' => $slug,
                    'cor_hex' => $faixa['cor_hex'],
                    'flux_variant' => $faixa['flux_variant'],
                    'titulo' => $faixa['titulo'],
                    'mensagem' => $faixa['mensagem'],
                    'cta_texto' => $faixa['cta_texto'],
                    'cartilha_slug' => $faixa['cartilha_slug'],
                    'fechamento' => config('quiz.fechamento'),
                    'pontuacao_total' => $total,
                ];
            }
        }

        // Nunca deve acontecer dado o range validado acima.
        throw new InvalidArgumentException("Nenhuma faixa de classificação cobre a pontuação {$total}.");
    }

    /**
     * Soma das respostas (cada uma 0–2).
     *
     * @param  array<int, int>  $respostas
     */
    public function pontuacaoTotal(array $respostas): int
    {
        return array_sum(array_map(fn ($r) => (int) $r, $respostas));
    }
}
