@props([
    'current' => 1,
    'total' => 3,
    'respondidasNoEixo' => 0,
    'perguntasNoEixo' => 3,
    'percent' => 0,
    'eixoNome' => '',
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="mb-2 flex items-center justify-between">
        <flux:badge color="red" size="sm">Eixo {{ $current }} de {{ $total }}</flux:badge>
        <flux:text size="sm" class="font-medium text-zinc-500 dark:text-zinc-400">{{ $percent }}%</flux:text>
    </div>

    <div class="flex gap-1.5" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
        @for ($i = 1; $i <= $total; $i++)
            @php
                $w = $i < $current ? 100 : ($i === $current ? (int) round(($respondidasNoEixo / max(1, $perguntasNoEixo)) * 100) : 0);
            @endphp
            <div class="h-2 flex-1 overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-800">
                <div
                    class="h-full rounded-full bg-brand-600 transition-all duration-500 ease-out dark:bg-brand-400"
                    style="width: {{ $w }}%"
                ></div>
            </div>
        @endfor
    </div>

    @if ($eixoNome)
        <flux:heading size="lg" level="2" class="mt-4">{{ $eixoNome }}</flux:heading>
    @endif
</div>
