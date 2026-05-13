<x-layouts.app :title="config('app.name') . ' — Diagnóstico Jurídico'">
    <div class="flex flex-col items-center text-center">
        <flux:badge color="red" size="sm" class="mb-6">Empreende Brazil 2026</flux:badge>

        <h1 class="max-w-xl text-balance text-3xl font-bold leading-tight text-white sm:text-4xl">
            Diagnóstico Jurídico<br>da sua Empresa
        </h1>

        <p class="mt-5 max-w-md text-lg leading-relaxed text-zinc-300">
            9 perguntas rápidas em 3 eixos.<br class="hidden sm:block">
            Ao final, você recebe um diagnóstico e uma cartilha exclusiva.
        </p>

        <div class="my-8 grid w-full max-w-md gap-3 text-left">
            @foreach ([
                ['scale', 'Reforma Tributária e Fluxo de Caixa'],
                ['building-office-2', 'Governança Societária e Contratual'],
                ['light-bulb', 'Propriedade Intelectual e Ativos Digitais'],
            ] as [$icon, $label])
                <flux:card class="flex items-center gap-3 !py-4">
                    <flux:icon :name="$icon" variant="mini" class="size-5 shrink-0 text-brand-500" />
                    <span class="font-medium text-zinc-200">{{ $label }}</span>
                </flux:card>
            @endforeach
        </div>

        <flux:button
            :href="route('diagnostico')"
            variant="primary"
            icon-trailing="arrow-right"
            class="min-h-14 w-full max-w-md !text-lg !font-semibold"
            wire:navigate
        >
            Iniciar Diagnóstico
        </flux:button>

        <p class="mt-4 text-base text-zinc-500">Leva menos de 3 minutos.</p>
    </div>
</x-layouts.app>
