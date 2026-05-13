<div>
    <div class="mb-6 text-center">
        <flux:badge color="blue" size="sm" class="mb-3">Etapa 1 de 2</flux:badge>
        <flux:heading size="lg" level="1">Antes de começar</flux:heading>
        <flux:subheading>Precisamos te identificar para enviar seu diagnóstico e a cartilha.</flux:subheading>
    </div>

    <flux:card>
        <form wire:submit="submit" class="flex flex-col gap-5">
            {{-- Honeypot: escondido de humanos, preenchido por bots --}}
            <div class="hidden" aria-hidden="true">
                <label>Não preencha este campo
                    <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
                </label>
            </div>

            <flux:input
                wire:model="nome"
                label="Nome completo"
                placeholder="Maria Silva"
                autocomplete="name"
                required
            />

            <flux:input
                wire:model="empresa"
                label="Empresa"
                placeholder="Razão social ou nome fantasia"
                autocomplete="organization"
                required
            />

            <flux:input
                wire:model="email"
                type="email"
                label="E-mail"
                placeholder="maria@empresa.com.br"
                autocomplete="email"
                inputmode="email"
                required
            />

            <flux:select wire:model="area_atuacao" label="Área de atuação" placeholder="Selecione..." required>
                @foreach ($areas as $area)
                    <flux:select.option :value="$area">{{ $area }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:checkbox
                wire:model="consentimento_lgpd"
                label="Autorizo o uso dos meus dados para receber o diagnóstico e materiais relacionados, conforme a Política de Privacidade."
            />

            <flux:button type="submit" variant="primary" icon-trailing="arrow-right" class="mt-1 min-h-12 w-full text-base">
                <span wire:loading.remove wire:target="submit">Começar o diagnóstico</span>
                <span wire:loading wire:target="submit">Aguarde...</span>
            </flux:button>
        </form>
    </flux:card>

    <flux:text size="sm" class="mt-4 text-center text-zinc-400 dark:text-zinc-600">
        Seus dados são tratados conforme a LGPD. Veja a
        <a href="{{ route('privacidade') }}" class="underline" wire:navigate>Política de Privacidade</a>.
    </flux:text>
</div>
