<x-layouts.app :title="config('app.name') . ' — Diagnóstico Jurídico'">
    <div class="flex flex-col items-center text-center">
        <flux:badge color="red" size="sm" class="mb-6">Empreende Brazil 2026</flux:badge>

        <flux:heading size="xl" level="1" class="max-w-lg text-balance">
            Diagnóstico Jurídico da sua Empresa
        </flux:heading>

        <flux:text class="mt-4 max-w-md text-base">
            9 perguntas rápidas em 3 eixos — Reforma Tributária, Governança Societária e
            Propriedade Intelectual. Ao final você recebe um diagnóstico e uma cartilha exclusiva.
        </flux:text>

        <div class="my-8 grid w-full max-w-md gap-3 text-left">
            @foreach ([
                ['scale', 'Reforma Tributária e Fluxo de Caixa'],
                ['building-office-2', 'Governança Societária e Contratual'],
                ['light-bulb', 'Propriedade Intelectual e Ativos Digitais'],
            ] as [$icon, $label])
                <flux:card class="flex items-center gap-3 !py-3">
                    <flux:icon :name="$icon" variant="mini" class="shrink-0 text-brand-600 dark:text-brand-400" />
                    <flux:text class="font-medium">{{ $label }}</flux:text>
                </flux:card>
            @endforeach
        </div>

        <flux:button
            :href="route('diagnostico')"
            variant="primary"
            icon-trailing="arrow-right"
            class="min-h-12 w-full max-w-md text-base"
            wire:navigate
        >
            Iniciar Diagnóstico
        </flux:button>

        <flux:text size="sm" class="mt-3 text-zinc-400 dark:text-zinc-600">
            Leva menos de 3 minutos.
        </flux:text>
    </div>
</x-layouts.app>
