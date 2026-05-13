<div>
    <div class="mb-6 text-center">
        <flux:badge color="red" size="sm" class="mb-3">Diagnóstico concluído</flux:badge>
        <flux:heading size="lg" level="1">
            {{ $lead?->nome ? \Illuminate\Support\Str::of($lead->nome)->before(' ')->append(', aqui está seu resultado') : 'Seu resultado' }}
        </flux:heading>
        @if ($lead?->empresa)
            <flux:subheading>{{ $lead->empresa }}</flux:subheading>
        @endif
    </div>

    <x-quiz.resultado-card :diag="$diag" :pontuacao="(int) $resposta->pontuacao_total" />

    <flux:card class="mt-6">
        <div class="flex flex-col gap-4">
            <div class="flex items-start gap-3">
                <flux:icon.document-arrow-down variant="mini" class="mt-0.5 shrink-0 text-brand-600 dark:text-brand-400" />
                <div>
                    <flux:heading size="md" level="2">{{ $cartilha['titulo'] }}</flux:heading>
                    <flux:text size="sm">{{ $cartilha['subtitulo'] }}</flux:text>
                </div>
            </div>

            <flux:button
                :href="$cartilhaUrl"
                wire:click="marcarCartilhaBaixada"
                variant="filled"
                icon="arrow-down-tray"
                class="w-full"
            >
                {{ $diag['cta_texto'] }}
            </flux:button>
        </div>
    </flux:card>

    <flux:separator class="my-7" text="Próximo passo" />

    <flux:callout variant="secondary" icon="chat-bubble-left-right">
        <flux:callout.text>{{ $fechamento }}</flux:callout.text>
    </flux:callout>

    <flux:button
        :href="$cafeUrl"
        variant="primary"
        icon="calendar-days"
        class="mt-4 min-h-12 w-full text-base"
    >
        Café com o Advogado
    </flux:button>

    <div class="mt-8 text-center">
        <flux:button :href="route('home')" variant="ghost" size="sm" icon="arrow-path" wire:navigate>
            Próximo participante
        </flux:button>
    </div>

    @if ($lead?->email)
        <flux:text size="sm" class="mt-4 text-center text-zinc-400 dark:text-zinc-600">
            Enviamos uma cópia deste diagnóstico e a cartilha para <span class="font-medium">{{ $lead->email }}</span>.
        </flux:text>
    @endif
</div>
