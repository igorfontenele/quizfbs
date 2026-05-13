@php
    $accents = ['verde' => '#22c55e', 'amarelo' => '#eab308', 'vermelho' => '#ef4444'];
    $accent = $accents[$resposta?->classificacao] ?? '#bd213f';
    $conteudoView = $slug === 'cartilha-de-expansao' ? 'cartilhas.conteudo.expansao' : 'cartilhas.conteudo.gestao-riscos';
    $urgente = $resposta && $resposta->classificacao === 'vermelho';
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
@endphp

<x-layouts.app :title="$cartilha['titulo'].' — FBS'" containerClass="max-w-3xl">
    <style>
        .cartilha-body { color: #d4d4d8; line-height: 1.7; }
        .cartilha-body h3 {
            margin-top: 2rem; margin-bottom: .6rem; padding-bottom: .5rem;
            font-size: 1.35rem; font-weight: 700; color: #fafafa;
            border-bottom: 2px solid {{ $accent }};
        }
        .cartilha-body h3:first-child { margin-top: 0; }
        .cartilha-body h4 { margin-top: 1.5rem; margin-bottom: .35rem; font-size: 1.05rem; font-weight: 600; color: {{ $accent }}; }
        .cartilha-body p { margin-bottom: .9rem; }
        .cartilha-body ul { margin: .35rem 0 1.1rem 1.4rem; list-style: disc; }
        .cartilha-body li { margin-bottom: .5rem; }
        .cartilha-body strong { color: #fafafa; }
        .cartilha-body em { color: #a1a1aa; }
        .cartilha-body .callout {
            margin: 1.2rem 0; padding: .9rem 1.1rem; border-radius: .6rem;
            background: rgba(255,255,255,.04); border-left: 3px solid {{ $accent }};
        }
        .cartilha-body .callout strong { color: {{ $accent }}; }
        .cartilha-body .pagebreak { display: none; }
        .cartilha-body table.checklist { width: 100%; border-collapse: collapse; margin: .6rem 0 1.2rem; }
        .cartilha-body table.checklist td { padding: .7rem 0; vertical-align: top; border-bottom: 1px solid rgba(255,255,255,.08); font-size: .98rem; }
        .cartilha-body table.checklist td.box { width: 2rem; color: {{ $accent }}; font-size: 1.2rem; }
    </style>

    <div>
        {{-- Cabeçalho da cartilha --}}
        <div class="mb-7 text-center">
            <flux:badge color="red" size="sm" class="mb-3">Sua cartilha</flux:badge>
            <h1 class="text-2xl font-bold leading-tight text-white sm:text-3xl">{{ $cartilha['titulo'] }}</h1>
            <p class="mt-2 text-base text-zinc-400">{{ $cartilha['subtitulo'] }}</p>
            @if ($lead?->nome)
                <p class="mt-3 text-sm text-zinc-500">
                    Preparada para <span class="font-medium text-zinc-400">{{ $lead->nome }}</span>@if($lead->empresa) · {{ $lead->empresa }}@endif
                    @if($resposta?->classificacao) · diagnóstico <span class="font-medium" style="color: {{ $accent }}">{{ $labels[$resposta->classificacao] }}</span>@endif
                </p>
            @endif
        </div>

        <flux:card class="!p-6 sm:!p-8">
            <div class="cartilha-body">
                @include($conteudoView, ['urgente' => $urgente, 'resposta' => $resposta])
            </div>
        </flux:card>

        {{-- Ações --}}
        <div class="mt-7 flex flex-col gap-3 sm:flex-row">
            @if ($cafeUrl)
                <flux:button :href="$cafeUrl" variant="primary" icon="calendar-days" class="min-h-14 flex-1 !text-base !font-semibold">
                    Café com o Advogado
                </flux:button>
            @endif
            @if ($pdfUrl)
                <flux:button :href="$pdfUrl" variant="filled" icon="arrow-down-tray" class="min-h-14 sm:flex-none">
                    Baixar em PDF
                </flux:button>
            @endif
        </div>

        <div class="mt-8 text-center">
            <flux:button :href="route('home')" variant="ghost" icon="arrow-path" class="min-h-12" wire:navigate>
                Próximo participante
            </flux:button>
        </div>

        <p class="mt-6 text-center text-xs leading-relaxed text-zinc-600">
            Conteúdo de propriedade intelectual de Fonseca Brasil Serrão Advogados. Uso pessoal e informativo;
            não constitui parecer jurídico individualizado.
        </p>
    </div>
</x-layouts.app>
