@props([
    'current' => 1,
    'total' => 3,
    'respondidasNoEixo' => 0,
    'perguntasNoEixo' => 3,
    'percent' => 0,
    'eixoNome' => '',
])

<div {{ $attributes->merge(['class' => 'mb-7']) }}>
    <div class="mb-2.5 flex items-center justify-between">
        <flux:badge color="red" size="sm">Eixo {{ $current }} de {{ $total }}</flux:badge>
        <span class="text-sm font-semibold text-zinc-400">{{ $percent }}%</span>
    </div>

    <div class="flex gap-1.5" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
        @for ($i = 1; $i <= $total; $i++)
            @php
                $w = $i < $current ? 100 : ($i === $current ? (int) round(($respondidasNoEixo / max(1, $perguntasNoEixo)) * 100) : 0);
            @endphp
            <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-zinc-800">
                <div
                    class="h-full rounded-full bg-brand-500 transition-all duration-500 ease-out"
                    style="width: {{ $w }}%"
                ></div>
            </div>
        @endfor
    </div>

    @if ($eixoNome)
        <h2 class="mt-5 text-xl font-bold text-white sm:text-2xl">{{ $eixoNome }}</h2>
    @endif
</div>
