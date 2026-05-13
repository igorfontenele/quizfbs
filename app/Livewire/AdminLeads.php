<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\QuizResposta;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['containerClass' => 'max-w-6xl'])]
#[Title('Painel — Diagnóstico Jurídico')]
class AdminLeads extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $classificacao = '';

    #[Url]
    public string $sort = 'recentes';

    public ?int $detalheRespostaId = null;

    public function updating($name): void
    {
        if (in_array($name, ['search', 'classificacao', 'sort'], true)) {
            $this->resetPage();
        }
    }

    public function limparFiltros(): void
    {
        $this->reset('search', 'classificacao', 'sort');
        $this->resetPage();
    }

    public function verRespostas(int $respostaId): void
    {
        $this->detalheRespostaId = $respostaId;
        $this->modal('respostas')->show();
    }

    protected function baseQuery(): Builder
    {
        return Lead::query()
            ->with('quizResposta')
            ->when($this->search !== '', function (Builder $q) {
                $term = '%'.str_replace(['%', '_'], ['\%', '\_'], $this->search).'%';
                $q->where(function (Builder $q) use ($term) {
                    $q->where('nome', 'like', $term)
                        ->orWhere('empresa', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('telefone', 'like', $term)
                        ->orWhere('area_atuacao', 'like', $term);
                });
            })
            ->when($this->classificacao !== '', function (Builder $q) {
                if ($this->classificacao === 'incompleto') {
                    $q->whereDoesntHave('quizResposta', fn (Builder $r) => $r->whereNotNull('classificacao'));
                } else {
                    $q->whereHas('quizResposta', fn (Builder $r) => $r->where('classificacao', $this->classificacao));
                }
            });
    }

    public function exportUrl(): string
    {
        return route('admin.export', array_filter([
            'q' => $this->search ?: null,
            'classificacao' => $this->classificacao ?: null,
        ]));
    }

    public function render()
    {
        $query = $this->baseQuery();

        match ($this->sort) {
            'antigos' => $query->oldest(),
            'nome' => $query->orderBy('nome'),
            default => $query->latest(),
        };

        $leads = $query->paginate(20);

        $stats = [
            'total' => Lead::count(),
            'completos' => QuizResposta::whereNotNull('classificacao')->count(),
            'verde' => QuizResposta::where('classificacao', 'verde')->count(),
            'amarelo' => QuizResposta::where('classificacao', 'amarelo')->count(),
            'vermelho' => QuizResposta::where('classificacao', 'vermelho')->count(),
            'cartilhas' => QuizResposta::where('cartilha_baixada', true)->count(),
            'ctas' => QuizResposta::where('cta_clicado', true)->count(),
        ];

        $detalhe = $this->detalheRespostaId
            ? QuizResposta::with('lead')->find($this->detalheRespostaId)
            : null;

        return view('livewire.admin-leads', [
            'leads' => $leads,
            'stats' => $stats,
            'detalhe' => $detalhe,
            'eixos' => config('quiz.eixos'),
        ]);
    }
}
