<?php

namespace App\Livewire;

use App\Mail\DiagnosticoResultado;
use App\Models\Lead;
use App\Models\QuizResposta;
use App\Services\QuizClassifierService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Diagnóstico Jurídico')]
class QuizRunner extends Component
{
    public Lead $lead;

    public int $eixoAtual = 1;

    /** @var array<string, int|null> q1..q9 */
    public array $respostas = [];

    public bool $analisando = false;

    public ?string $resultadoUrl = null;

    public function mount(Lead $lead)
    {
        $this->lead = $lead;

        // Se este lead já concluiu o quiz, manda direto pro resultado.
        $existente = $lead->quizResposta;
        if ($existente && $existente->classificacao !== null) {
            return $this->redirect(URL::signedRoute('resultado.show', ['resposta' => $existente]));
        }

        foreach (range(1, 9) as $n) {
            $this->respostas["q{$n}"] = null;
        }
    }

    /** Perguntas do eixo atual (3 por eixo). */
    public function getPerguntasDoEixoProperty(): array
    {
        return config("quiz.eixos.{$this->eixoAtual}.perguntas", []);
    }

    public function getNomeDoEixoProperty(): string
    {
        return config("quiz.eixos.{$this->eixoAtual}.nome", '');
    }

    public function getTotalEixosProperty(): int
    {
        return count(config('quiz.eixos'));
    }

    public function getProgressoProperty(): int
    {
        $respondidas = collect($this->respostas)->filter(fn ($v) => $this->respostaValida($v))->count();

        return (int) round(($respondidas / 9) * 100);
    }

    public function getRespondidasNoEixoProperty(): int
    {
        return collect(array_keys($this->perguntasDoEixo))
            ->filter(fn ($k) => $this->respostaValida($this->respostas[$k] ?? null))
            ->count();
    }

    private function respostaValida(mixed $v): bool
    {
        return is_numeric($v) && in_array((int) $v, [0, 1, 2], true);
    }

    private function eixoCompleto(int $eixo): bool
    {
        foreach (array_keys(config("quiz.eixos.{$eixo}.perguntas")) as $key) {
            if (! $this->respostaValida($this->respostas[$key] ?? null)) {
                return false;
            }
        }

        return true;
    }

    public function proximo(): void
    {
        if (! $this->eixoCompleto($this->eixoAtual)) {
            $this->addError('eixo', 'Responda todas as perguntas deste eixo para continuar.');

            return;
        }

        $this->resetErrorBag('eixo');

        if ($this->eixoAtual < $this->totalEixos) {
            $this->eixoAtual++;
            $this->dispatch('eixo-changed');
        } else {
            $this->finalizar();
        }
    }

    public function voltar(): void
    {
        if ($this->eixoAtual > 1) {
            $this->eixoAtual--;
            $this->resetErrorBag('eixo');
            $this->dispatch('eixo-changed');
        }
    }

    public function finalizar(): void
    {
        // Garante que as 9 estão respondidas (0, 1 ou 2).
        $valores = [];
        foreach (range(1, 9) as $n) {
            $v = $this->respostas["q{$n}"] ?? null;
            if (! $this->respostaValida($v)) {
                $this->addError('eixo', 'Há perguntas sem resposta. Revise os eixos anteriores.');

                return;
            }
            $valores["q{$n}"] = (int) $v;
        }

        $classifier = app(QuizClassifierService::class);
        $total = $classifier->pontuacaoTotal(array_values($valores));
        $diag = $classifier->classify($total);

        $resposta = QuizResposta::updateOrCreate(
            ['lead_id' => $this->lead->id],
            array_merge($valores, [
                'pontuacao_total' => $total,
                'classificacao' => $diag['classificacao'],
            ]),
        );

        // E-mail assíncrono com o diagnóstico + cartilha em anexo (fila database).
        Mail::to($this->lead->email)->queue(new DiagnosticoResultado($resposta->fresh('lead')));

        $this->resultadoUrl = URL::signedRoute('resultado.show', ['resposta' => $resposta]);
        $this->analisando = true;
    }

    public function render()
    {
        return view('livewire.quiz-runner');
    }
}
