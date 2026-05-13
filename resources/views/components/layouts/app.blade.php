<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0a0a0a">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="flex min-h-full flex-col bg-zinc-50 text-zinc-800 antialiased dark:bg-zinc-950 dark:text-zinc-200">

    {{-- Faixa superior com as cores do FBS --}}
    <div class="h-1.5 w-full bg-gradient-to-r from-fbs-crimson via-fbs-orange to-fbs-gold"></div>

    @php($__container = $containerClass ?? 'max-w-2xl')
    <div class="mx-auto flex w-full {{ $__container }} flex-1 flex-col px-4 py-6 sm:py-10">

        <header class="mb-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center" wire:navigate aria-label="FBS — Fonseca Brasil Serrão Advogados">
                <span class="inline-flex items-center rounded-md bg-zinc-950 px-3 py-2 shadow-sm ring-1 ring-zinc-200 dark:ring-white/10">
                    <img src="{{ asset('images/fbs-white.svg') }}" alt="FBS — Fonseca Brasil Serrão Advogados" class="h-5 w-auto sm:h-6">
                </span>
            </a>
            <div class="flex items-center gap-2">
                @isset($headerActions){{ $headerActions }}@endisset
                <flux:button
                    x-data
                    x-on:click="$flux.dark = ! $flux.dark"
                    icon="moon"
                    variant="subtle"
                    size="sm"
                    square
                    aria-label="Alternar tema"
                />
            </div>
        </header>

        <main class="flex flex-1 flex-col justify-center">
            {{ $slot }}
        </main>
    </div>

    {{-- Rodapé: parceria Empreende Brazil × FBS --}}
    <footer class="bg-zinc-950 text-white">
        {{-- micro-faixa com as cores do FBS --}}
        <div class="h-1 w-full bg-gradient-to-r from-fbs-crimson via-fbs-orange to-fbs-gold"></div>
        <div class="mx-auto flex {{ $__container }} flex-col items-center gap-4 px-4 py-8 text-center">
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                <span class="rounded-md bg-white px-3 py-2 shadow-sm">
                    <img src="{{ asset('images/empreende-brazil.png') }}" alt="Empreende Brazil" class="h-6 w-auto sm:h-7">
                </span>
                <span aria-hidden="true" class="text-lg font-light text-white/30">×</span>
                <img src="{{ asset('images/fbs-white.svg') }}" alt="FBS — Fonseca Brasil Serrão Advogados" class="h-7 w-auto sm:h-8">
            </div>
            <p class="text-xs text-white/60">Diagnóstico Jurídico &middot; Empreende Brazil 2026</p>
            <a href="{{ route('privacidade') }}" class="text-xs text-white/60 underline underline-offset-2 transition hover:text-white" wire:navigate>
                Política de Privacidade
            </a>
        </div>
    </footer>

    @fluxScripts
</body>
</html>
