<div>
    @php
        $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
        $variantBadge = ['verde' => 'lime', 'amarelo' => 'yellow', 'vermelho' => 'red'];
        $abandonados = max(0, $stats['total'] - $stats['completos']);
    @endphp

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="lg" level="1">Painel — Diagnóstico Jurídico</flux:heading>
            <flux:subheading>Leads e respostas captados no Empreende Brazil 2026.</flux:subheading>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="shrink-0">
            @csrf
            <flux:button type="submit" variant="ghost" size="sm">Sair</flux:button>
        </form>
    </div>

    {{-- Estatísticas --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <flux:card class="!py-4">
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">Leads totais</flux:text>
            <flux:heading size="lg" class="mt-1">{{ $stats['total'] }}</flux:heading>
            <flux:text size="sm" class="mt-0.5 text-zinc-400">{{ $stats['completos'] }} concluíram · {{ $abandonados }} abandonaram</flux:text>
        </flux:card>
        <flux:card class="!py-4">
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">Verde / Amarelo / Vermelho</flux:text>
            <flux:heading size="lg" class="mt-1">
                <span class="text-lime-600 dark:text-lime-500">{{ $stats['verde'] }}</span>
                <span class="text-zinc-300 dark:text-zinc-500">/</span>
                <span class="text-yellow-600 dark:text-yellow-500">{{ $stats['amarelo'] }}</span>
                <span class="text-zinc-300 dark:text-zinc-500">/</span>
                <span class="text-red-600 dark:text-red-500">{{ $stats['vermelho'] }}</span>
            </flux:heading>
            <flux:text size="sm" class="mt-0.5 text-zinc-400">distribuição dos diagnósticos</flux:text>
        </flux:card>
        <flux:card class="!py-4">
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">Cartilhas baixadas</flux:text>
            <flux:heading size="lg" class="mt-1">{{ $stats['cartilhas'] }}</flux:heading>
            <flux:text size="sm" class="mt-0.5 text-zinc-400">{{ $stats['completos'] ? round($stats['cartilhas'] / $stats['completos'] * 100) : 0 }}% dos concluídos</flux:text>
        </flux:card>
        <flux:card class="!py-4">
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">Cliques no "Café"</flux:text>
            <flux:heading size="lg" class="mt-1">{{ $stats['ctas'] }}</flux:heading>
            <flux:text size="sm" class="mt-0.5 text-zinc-400">{{ $stats['completos'] ? round($stats['ctas'] / $stats['completos'] * 100) : 0 }}% dos concluídos</flux:text>
        </flux:card>
    </div>

    {{-- Filtros --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-end">
        <flux:input
            wire:model.live.debounce.400ms="search"
            label="Buscar"
            placeholder="Nome, empresa, e-mail ou área..."
            icon="magnifying-glass"
            clearable
            class="sm:flex-1"
        />
        <flux:select wire:model.live="classificacao" label="Diagnóstico" class="sm:w-48">
            <flux:select.option value="">Todos</flux:select.option>
            <flux:select.option value="verde">Verde</flux:select.option>
            <flux:select.option value="amarelo">Amarelo</flux:select.option>
            <flux:select.option value="vermelho">Vermelho</flux:select.option>
            <flux:select.option value="incompleto">Não concluídos</flux:select.option>
        </flux:select>
        <flux:select wire:model.live="sort" label="Ordenar" class="sm:w-44">
            <flux:select.option value="recentes">Mais recentes</flux:select.option>
            <flux:select.option value="antigos">Mais antigos</flux:select.option>
            <flux:select.option value="nome">Nome (A–Z)</flux:select.option>
        </flux:select>
        <flux:button :href="$this->exportUrl()" icon="arrow-down-tray" variant="filled">Exportar CSV</flux:button>
    </div>

    @if ($search || $classificacao || $sort !== 'recentes')
        <div class="mt-2">
            <flux:button wire:click="limparFiltros" variant="ghost" size="sm" icon="x-mark">Limpar filtros</flux:button>
        </div>
    @endif

    {{-- Tabela --}}
    <div class="mt-4 overflow-x-auto">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Lead</flux:table.column>
                <flux:table.column>Empresa / Área</flux:table.column>
                <flux:table.column>Diagnóstico</flux:table.column>
                <flux:table.column>Pontos</flux:table.column>
                <flux:table.column>Cartilha</flux:table.column>
                <flux:table.column>Café</flux:table.column>
                <flux:table.column>Data</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($leads as $lead)
                    @php $r = $lead->quizResposta; $c = $r?->classificacao; @endphp
                    <flux:table.row :key="$lead->id">
                        <flux:table.cell>
                            <div class="font-medium text-zinc-800 dark:text-zinc-200">{{ $lead->nome }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $lead->email }}</div>
                            @if ($lead->telefone)
                                <div class="text-xs">
                                    @if ($url = $lead->whatsappUrl())
                                        <a href="{{ $url }}" target="_blank" rel="noopener" class="text-lime-600 hover:underline dark:text-lime-500">
                                            {{ $lead->telefone }}
                                        </a>
                                    @else
                                        <span class="text-zinc-500 dark:text-zinc-400">{{ $lead->telefone }}</span>
                                    @endif
                                </div>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div>{{ $lead->empresa }}</div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $lead->area_atuacao }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($c)
                                <flux:badge size="sm" :color="$variantBadge[$c]">{{ $labels[$c] }}</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">Não concluído</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>{{ $r && $r->pontuacao_total !== null ? $r->pontuacao_total.'/18' : '—' }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($r?->cartilha_baixada)<flux:icon.check variant="micro" class="text-lime-600" />@else<span class="text-zinc-300 dark:text-zinc-500">—</span>@endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($r?->cta_clicado)<flux:icon.check variant="micro" class="text-lime-600" />@else<span class="text-zinc-300 dark:text-zinc-500">—</span>@endif
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap text-xs text-zinc-500 dark:text-zinc-400">
                            {{ $lead->created_at?->format('d/m/Y H:i') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($r)
                                <flux:button size="xs" variant="ghost" icon="eye" wire:click="verRespostas({{ $r->id }})">Respostas</flux:button>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="8">
                            <div class="py-8 text-center text-zinc-500 dark:text-zinc-400">Nenhum lead encontrado.</div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <div class="mt-4">
        <flux:pagination :paginator="$leads" />
    </div>

    {{-- Modal com as 9 respostas --}}
    <flux:modal name="respostas" class="w-full max-w-xl">
        @if ($detalhe)
            <div class="space-y-5">
                <div>
                    <flux:heading size="lg">Respostas — {{ $detalhe->lead?->nome }}</flux:heading>
                    <flux:subheading>
                        {{ $detalhe->lead?->empresa }} ·
                        @if ($detalhe->classificacao)
                            <flux:badge size="sm" :color="$variantBadge[$detalhe->classificacao]" inset="top bottom">{{ $labels[$detalhe->classificacao] }}</flux:badge>
                            {{ $detalhe->pontuacao_total }}/18 pontos
                        @else
                            quiz não concluído
                        @endif
                    </flux:subheading>
                </div>

                @foreach ($eixos as $numEixo => $eixo)
                    <div>
                        <flux:text size="sm" class="font-semibold uppercase tracking-wide text-brand-600 dark:text-brand-400">
                            Eixo {{ $numEixo }} — {{ $eixo['nome'] }}
                        </flux:text>
                        <div class="mt-2 space-y-2">
                            @foreach ($eixo['perguntas'] as $key => $pergunta)
                                @php $val = $detalhe->{$key}; @endphp
                                <div class="rounded-lg border border-zinc-200 p-3 text-sm dark:border-zinc-700">
                                    <div class="font-medium text-zinc-700 dark:text-zinc-300">{{ strtoupper($key) }}. {{ $pergunta['enunciado'] }}</div>
                                    <div class="mt-1 text-zinc-600 dark:text-zinc-400">
                                        @if ($val === null)
                                            <span class="italic text-zinc-400">sem resposta</span>
                                        @else
                                            <span class="font-semibold {{ $val == 0 ? 'text-lime-600' : ($val == 1 ? 'text-yellow-600' : 'text-red-600') }}">[{{ $val }}]</span>
                                            {{ $pergunta['opcoes'][$val] ?? '—' }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end">
                    <flux:modal.close><flux:button variant="ghost">Fechar</flux:button></flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
