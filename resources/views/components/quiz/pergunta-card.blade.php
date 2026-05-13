@props([
    'numero',          // 1..9 (número global da pergunta)
    'wireKey',         // 'q1'..'q9' — chave dentro do array $respostas no componente
    'pergunta',        // ['titulo' => ..., 'enunciado' => ..., 'opcoes' => [0=>...,1=>...,2=>...]]
])

<flux:card wire:key="pergunta-{{ $wireKey }}" class="!p-5 sm:!p-6">
    <div class="mb-4 flex items-start gap-3">
        <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full bg-brand-600 text-base font-bold text-white">
            {{ $numero }}
        </span>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-brand-500">{{ $pergunta['titulo'] }}</p>
            <h3 class="mt-1 text-lg font-semibold leading-snug text-white sm:text-xl">{{ $pergunta['enunciado'] }}</h3>
        </div>
    </div>

    <flux:radio.group wire:model.live="respostas.{{ $wireKey }}" variant="cards" class="!flex-col !gap-2.5">
        @foreach ($pergunta['opcoes'] as $valor => $texto)
            <flux:radio :value="$valor" :label="$texto" class="min-h-14 text-base" />
        @endforeach
    </flux:radio.group>
</flux:card>
