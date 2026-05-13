@props([
    'diag',          // array do QuizClassifierService::classify()
    'pontuacao',     // 0..18
    'maxPontuacao' => \App\Services\QuizClassifierService::PONTUACAO_MAXIMA,
])

@php
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
@endphp

<flux:callout :variant="$diag['flux_variant']" :icon="match($diag['classificacao']) {
    'verde' => 'check-badge',
    'amarelo' => 'exclamation-triangle',
    'vermelho' => 'shield-exclamation',
}">
    <flux:callout.heading>
        Diagnóstico {{ $labels[$diag['classificacao']] }} — {{ $diag['titulo'] }}
    </flux:callout.heading>
    <flux:callout.text>{{ $diag['mensagem'] }}</flux:callout.text>
</flux:callout>

<div class="mt-4">
    <div class="mb-1.5 flex items-center justify-between">
        <flux:text size="sm" class="font-medium text-zinc-500 dark:text-zinc-400">Índice de exposição a riscos</flux:text>
        <flux:text size="sm" class="font-semibold" style="color: {{ $diag['cor_hex'] }}">{{ $pontuacao }} / {{ $maxPontuacao }}</flux:text>
    </div>
    <div class="h-2.5 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-800">
        <div
            class="h-full rounded-full transition-all duration-700 ease-out"
            style="width: {{ (int) round(($pontuacao / max(1, $maxPontuacao)) * 100) }}%; background-color: {{ $diag['cor_hex'] }}"
        ></div>
    </div>
    <div class="mt-1 flex justify-between text-[10px] uppercase tracking-wide text-zinc-400 dark:text-zinc-600">
        <span>Maturidade</span>
        <span>Atenção</span>
        <span>Crítico</span>
    </div>
</div>
