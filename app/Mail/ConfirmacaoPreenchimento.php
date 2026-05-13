<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ConfirmacaoPreenchimento extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recebemos seus dados — Diagnóstico Jurídico | Empreende Brazil 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmacao',
            with: [
                'lead' => $this->lead,
                'saudacao' => 'Obrigado'.($this->lead->nome ? ', '.Str::before($this->lead->nome, ' ') : '').'!',
                'empresaFrase' => $this->lead->empresa ? ' referentes a '.$this->lead->empresa : '',
                'logoUrl' => url('/images/fbs-white.png'),
                'siteUrl' => url('/'),
            ],
        );
    }
}
