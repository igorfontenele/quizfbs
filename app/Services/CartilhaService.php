<?php

namespace App\Services;

use App\Models\QuizResposta;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Facades\View;
use RuntimeException;

class CartilhaService
{
    /**
     * Retorna a config de uma cartilha pelo slug, validando que ela existe.
     *
     * @return array{titulo: string, subtitulo: string, view: string, arquivo: string}
     */
    public function config(string $slug): array
    {
        $cartilha = config("quiz.cartilhas.{$slug}");

        if (! $cartilha) {
            throw new RuntimeException("Cartilha desconhecida: {$slug}");
        }

        return $cartilha;
    }

    /**
     * Verifica se a classificação da resposta corresponde a esta cartilha.
     */
    public function pertenceAResposta(string $slug, QuizResposta $resposta): bool
    {
        $classificacao = $resposta->classificacao;

        if ($classificacao === null) {
            return false;
        }

        return config("quiz.classificacoes.{$classificacao}.cartilha_slug") === $slug;
    }

    /**
     * Gera o PDF da cartilha.
     */
    public function gerarPdf(string $slug, ?QuizResposta $resposta = null): DomPdf
    {
        $cartilha = $this->config($slug);

        if (! View::exists($cartilha['view'])) {
            throw new RuntimeException("View da cartilha não encontrada: {$cartilha['view']}");
        }

        return Pdf::loadView($cartilha['view'], [
            'cartilha' => $cartilha,
            'slug' => $slug,
            'resposta' => $resposta,
            'lead' => $resposta?->lead,
            'gerado_em' => now(),
        ])->setPaper('a4');
    }

    public function nomeArquivo(string $slug): string
    {
        return $this->config($slug)['arquivo'];
    }
}
