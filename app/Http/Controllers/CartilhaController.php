<?php

namespace App\Http\Controllers;

use App\Models\QuizResposta;
use App\Services\CartilhaService;
use Symfony\Component\HttpFoundation\Response;

class CartilhaController extends Controller
{
    public function download(string $slug, QuizResposta $resposta, CartilhaService $cartilhas): Response
    {
        // Valida que esta cartilha corresponde à classificação da resposta.
        abort_unless($cartilhas->pertenceAResposta($slug, $resposta), 404);

        if (! $resposta->cartilha_baixada) {
            $resposta->forceFill(['cartilha_baixada' => true])->save();
        }

        $resposta->loadMissing('lead');

        return $cartilhas->gerarPdf($slug, $resposta)->download($cartilhas->nomeArquivo($slug));
    }
}
