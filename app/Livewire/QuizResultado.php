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

    /** Versão da cartilha exibida na tela (totem). */
    public string $cartilhaVerUrl = '';

    /** Download do PDF da cartilha. */
    public string $cartilhaPdfUrl = '';

    public string $cafeUrl = '';

    public function mount(QuizResposta $resposta): void
    {
        $resposta->loadMissing('lead');
        $this->resposta = $resposta;

        $this->diag = app(QuizClassifierService::class)->classify((int) $resposta->pontuacao_total);

        $slug = $this->diag['cartilha_slug'];
        $this->cartilhaVerUrl = URL::signedRoute('cartilha.ver', ['slug' => $slug, 'resposta' => $resposta]);
        $this->cartilhaPdfUrl = URL::signedRoute('cartilha.download', ['slug' => $slug, 'resposta' => $resposta]);
        $this->cafeUrl = route('cafe-com-advogado', ['resposta' => $resposta]);
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
