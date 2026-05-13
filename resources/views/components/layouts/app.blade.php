<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1d54f5">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-full bg-zinc-50 text-zinc-800 antialiased dark:bg-zinc-950 dark:text-zinc-200">
    <div class="mx-auto flex min-h-dvh w-full max-w-2xl flex-col px-4 py-6 sm:py-10">

        <header class="mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
                <flux:icon.scale variant="micro" class="text-brand-600 dark:text-brand-400" />
                <span class="text-sm font-semibold tracking-tight text-zinc-700 dark:text-zinc-300">
                    Empreende Brazil 2026
                </span>
            </a>
            <flux:button
                x-data
                x-on:click="$flux.dark = ! $flux.dark"
                icon="moon"
                variant="subtle"
                size="sm"
                square
                aria-label="Alternar tema"
            />
        </header>

        <main class="flex flex-1 flex-col justify-center">
            {{ $slot }}
        </main>

        <footer class="mt-10 text-center text-xs text-zinc-400 dark:text-zinc-600">
            <p>Diagnóstico Jurídico &middot; Empreende Brazil 2026</p>
            <p class="mt-1">
                <a href="{{ route('privacidade') }}" class="underline hover:text-zinc-600 dark:hover:text-zinc-400" wire:navigate>
                    Política de Privacidade
                </a>
            </p>
        </footer>
    </div>

    @fluxScripts
</body>
</html>
