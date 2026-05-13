<div>
    <div class="mb-6 text-center">
        <flux:badge color="red" size="sm" class="mb-3">Diagnóstico concluído</flux:badge>
        <h1 class="text-2xl font-bold leading-tight text-white sm:text-3xl">
            {{ $lead?->nome ? \Illuminate\Support\Str::of($lead->nome)->before(' ')->append(',') : 'Pronto!' }}<br>
            aqui está o seu resultado
        </h1>
        @if ($lead?->empresa)
            <p class="mt-2 text-base text-zinc-400">{{ $lead->empresa }}</p>
        @endif
    </div>

    <x-quiz.resultado-card :diag="$diag" :pontuacao="(int) $resposta->pontuacao_total" />

    <flux:card class="mt-6 !p-5 sm:!p-6">
        <div class="flex flex-col gap-4">
            <div class="flex items-start gap-3">
                <flux:icon.document-arrow-down variant="mini" class="mt-0.5 size-5 shrink-0 text-brand-500" />
                <div>
                    <h2 class="text-lg font-bold text-white sm:text-xl">{{ $cartilha['titulo'] }}</h2>
                    <p class="text-sm text-zinc-400">{{ $cartilha['subtitulo'] }}</p>
                </div>
            </div>

            <flux:button
                :href="$cartilhaUrl"
                wire:click="marcarCartilhaBaixada"
                variant="filled"
                icon="arrow-down-tray"
                class="min-h-14 w-full !text-base !font-semibold"
            >
                {{ $diag['cta_texto'] }}
            </flux:button>
        </div>
    </flux:card>

    <flux:separator class="my-8" text="Próximo passo" />

    <flux:callout variant="secondary" icon="chat-bubble-left-right">
        <flux:callout.text class="!text-base">{{ $fechamento }}</flux:callout.text>
    </flux:callout>

    <flux:button
        :href="$cafeUrl"
        variant="primary"
        icon="calendar-days"
        class="mt-4 min-h-14 w-full !text-lg !font-semibold"
    >
        Café com o Advogado
    </flux:button>

    <div class="mt-10 text-center">
        <flux:button :href="route('home')" variant="ghost" icon="arrow-path" class="min-h-12" wire:navigate>
            Próximo participante
        </flux:button>
    </div>

    @if ($lead?->email)
        <p class="mt-4 text-center text-sm text-zinc-500">
            Enviamos uma cópia deste diagnóstico e a cartilha para <span class="font-medium text-zinc-400">{{ $lead->email }}</span>.
        </p>
    @endif
</div>
