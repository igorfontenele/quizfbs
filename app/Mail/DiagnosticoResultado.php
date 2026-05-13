<?php

namespace App\Mail;

use App\Models\QuizResposta;
use App\Services\CartilhaService;
use App\Services\QuizClassifierService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class DiagnosticoResultado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public QuizResposta $resposta)
    {
        $this->resposta->loadMissing('lead');
    }

    public function envelope(): Envelope
    {
        $diag = $this->diagnostico();

        return new Envelope(
            subject: "Seu Diagnóstico Jurídico: {$diag['titulo']} — Empreende Brazil 2026",
        );
    }

    public function content(): Content
    {
        $diag = $this->diagnostico();

        return new Content(
            markdown: 'emails.diagnostico',
            with: [
                'diag' => $diag,
                'lead' => $this->resposta->lead,
                'pontuacao' => (int) $this->resposta->pontuacao_total,
                'maxPontuacao' => QuizClassifierService::PONTUACAO_MAXIMA,
                'resultadoUrl' => URL::signedRoute('resultado.show', ['resposta' => $this->resposta]),
                'cafeUrl' => route('cafe-com-advogado', ['resposta' => $this->resposta]),
                'cartilha' => config("quiz.cartilhas.{$diag['cartilha_slug']}"),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $diag = $this->diagnostico();
        $cartilhas = app(CartilhaService::class);
        $slug = $diag['cartilha_slug'];

        return [
            Attachment::fromData(
                fn () => $cartilhas->gerarPdf($slug, $this->resposta)->output(),
                $cartilhas->nomeArquivo($slug),
            )->withMime('application/pdf'),
        ];
    }

    /** @return array<string, mixed> */
    private function diagnostico(): array
    {
        return app(QuizClassifierService::class)->classify((int) $this->resposta->pontuacao_total);
    }
}
