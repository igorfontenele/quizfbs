@props([
    'numero',          // 1..9 (número global da pergunta)
    'wireKey',         // 'q1'..'q9' — chave dentro do array $respostas no componente
    'pergunta',        // ['titulo' => ..., 'enunciado' => ..., 'opcoes' => [0=>...,1=>...,2=>...]]
])

<flux:card wire:key="pergunta-{{ $wireKey }}">
    <div class="mb-3 flex items-start gap-3">
        <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-semibold text-white dark:bg-brand-500">
            {{ $numero }}
        </span>
        <div>
            <flux:text size="sm" class="font-semibold uppercase tracking-wide text-brand-600 dark:text-brand-400">
                {{ $pergunta['titulo'] }}
            </flux:text>
            <flux:heading size="md" level="3" class="mt-0.5 leading-snug">{{ $pergunta['enunciado'] }}</flux:heading>
        </div>
    </div>

    <flux:radio.group wire:model.live="respostas.{{ $wireKey }}" variant="cards" class="!gap-2 max-sm:flex-col">
        @foreach ($pergunta['opcoes'] as $valor => $texto)
            <flux:radio :value="$valor" :label="$texto" class="min-h-12" />
        @endforeach
    </flux:radio.group>
</flux:card>
