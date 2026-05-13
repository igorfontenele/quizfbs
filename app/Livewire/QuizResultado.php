<?php

namespace App\Livewire;

use App\Models\QuizResposta;
use App\Services\QuizClassifierService;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Seu Diagnóstico Jurídico')]
class QuizResultado extends Component
{
    public QuizResposta $resposta;

    /** @var array<string, mixed> */
    public array $diag = [];

    public string $cartilhaUrl = '';

    public string $cafeUrl = '';

    public function mount(QuizResposta $resposta): void
    {
        $resposta->loadMissing('lead');
        $this->resposta = $resposta;

        $this->diag = app(QuizClassifierService::class)->classify((int) $resposta->pontuacao_total);

        $this->cartilhaUrl = URL::signedRoute('cartilha.download', [
            'slug' => $this->diag['cartilha_slug'],
            'resposta' => $resposta,
        ]);

        $this->cafeUrl = route('cafe-com-advogado', ['resposta' => $resposta]);
    }

    public function marcarCartilhaBaixada(): void
    {
        if (! $this->resposta->cartilha_baixada) {
            $this->resposta->forceFill(['cartilha_baixada' => true])->save();
        }
    }

    public function render()
    {
        $cartilha = config("quiz.cartilhas.{$this->diag['cartilha_slug']}");

        return view('livewire.quiz-resultado', [
            'cartilha' => $cartilha,
            'fechamento' => config('quiz.fechamento'),
            'lead' => $this->resposta->lead,
        ]);
    }
}
