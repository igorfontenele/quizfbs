<div class="mx-auto w-full max-w-md">
    <div class="mb-7 text-center">
        <flux:badge color="red" size="sm" class="mb-3">Acesso restrito</flux:badge>
        <h1 class="text-2xl font-bold text-white sm:text-3xl">Painel do Diagnóstico</h1>
        <p class="mt-2 text-base text-zinc-400">Entre com suas credenciais para acessar os leads.</p>
    </div>

    <flux:card class="!p-6 sm:!p-7">
        <form wire:submit="submit" class="flex flex-col gap-5">
            <flux:input
                wire:model="usuario"
                label="Usuário"
                placeholder="admin"
                icon="user"
                autocomplete="username"
                autofocus
                required
            />

            <flux:input
                wire:model="senha"
                label="Senha"
                type="password"
                placeholder="••••••••"
                icon="lock-closed"
                autocomplete="current-password"
                viewable
                required
            />

            @error('usuario')
                <flux:callout variant="danger" icon="exclamation-circle" inline>
                    <flux:callout.text>{{ $message }}</flux:callout.text>
                </flux:callout>
            @enderror

            <flux:button
                type="submit"
                variant="primary"
                icon-trailing="arrow-right"
                class="min-h-14 w-full !text-base !font-semibold"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="submit">Entrar</span>
                <span wire:loading wire:target="submit">Verificando...</span>
            </flux:button>
        </form>
    </flux:card>

    <p class="mt-6 text-center text-xs text-zinc-500">
        Acesso destinado à equipe do <span class="font-medium text-zinc-400">Fonseca Brasil Serrão Advogados</span>.
    </p>
</div>
