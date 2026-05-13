<?php

namespace App\Http\Controllers;

use App\Models\QuizResposta;
use App\Services\CartilhaService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class CartilhaController extends Controller
{
    /** Versão em tela da cartilha (ideal para o totem). */
    public function show(string $slug, QuizResposta $resposta, CartilhaService $cartilhas): View
    {
        abort_unless($cartilhas->pertenceAResposta($slug, $resposta), 404);

        if (! $resposta->cartilha_baixada) {
            $resposta->forceFill(['cartilha_baixada' => true])->save();
        }

        $resposta->loadMissing('lead');

        return view('cartilhas.show', [
            'slug' => $slug,
            'cartilha' => $cartilhas->config($slug),
            'resposta' => $resposta,
            'lead' => $resposta->lead,
            'pdfUrl' => URL::signedRoute('cartilha.download', ['slug' => $slug, 'resposta' => $resposta]),
            'cafeUrl' => route('cafe-com-advogado', ['resposta' => $resposta]),
        ]);
    }

    /** Download do PDF (também usado como anexo no e-mail). */
    public function download(string $slug, QuizResposta $resposta, CartilhaService $cartilhas): Response
    {
        abort_unless($cartilhas->pertenceAResposta($slug, $resposta), 404);

        if (! $resposta->cartilha_baixada) {
            $resposta->forceFill(['cartilha_baixada' => true])->save();
        }

        $resposta->loadMissing('lead');

        return $cartilhas->gerarPdf($slug, $resposta)->download($cartilhas->nomeArquivo($slug));
    }
}
