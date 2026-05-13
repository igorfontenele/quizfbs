<div>
    <div class="mb-6 text-center">
        <flux:badge color="red" size="sm" class="mb-3">Etapa 1 de 2</flux:badge>
        <h1 class="text-2xl font-bold text-white sm:text-3xl">Antes de começar</h1>
        <p class="mx-auto mt-2 max-w-md text-base leading-relaxed text-zinc-400">
            Preencha seus dados para receber o diagnóstico e a cartilha.<br class="hidden sm:block">
            Todos os campos são obrigatórios.
        </p>
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

            <flux:input
                wire:model="telefone"
                type="tel"
                label="Telefone / WhatsApp"
                placeholder="(11) 99999-9999"
                autocomplete="tel"
                inputmode="tel"
                icon="phone"
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

            <flux:button type="submit" variant="primary" icon-trailing="arrow-right" class="mt-1 min-h-14 w-full !text-lg !font-semibold">
                <span wire:loading.remove wire:target="submit">Começar o diagnóstico</span>
                <span wire:loading wire:target="submit">Aguarde...</span>
            </flux:button>
        </form>
    </flux:card>

    <p class="mt-4 text-center text-sm text-zinc-500">
        Seus dados são tratados conforme a LGPD. Veja a
        <a href="{{ route('privacidade') }}" class="underline" wire:navigate>Política de Privacidade</a>.
    </p>
</div>
