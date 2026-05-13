<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="color-scheme" content="dark">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col bg-zinc-950 text-zinc-200 antialiased">
    @php($__container = $containerClass ?? 'max-w-2xl')

    {{-- Faixa de gradiente FBS --}}
    <div class="h-1.5 w-full bg-gradient-to-r from-fbs-crimson via-fbs-orange to-fbs-gold"></div>

    {{-- Navbar preta --}}
    <header class="border-b border-white/10 bg-zinc-950">
        <div class="mx-auto flex {{ $__container }} items-center justify-between px-4 py-3.5">
            <a href="{{ route('home') }}" wire:navigate aria-label="FBS — Fonseca Brasil Serrão Advogados">
                <img src="{{ asset('images/fbs-white.svg') }}" alt="FBS — Fonseca Brasil Serrão Advogados" class="h-8 w-auto sm:h-9">
            </a>
            @isset($headerActions)
                <div class="flex items-center gap-2">{{ $headerActions }}</div>
            @endisset
        </div>
    </header>

    <div class="mx-auto flex w-full {{ $__container }} flex-1 flex-col px-4 py-8 sm:py-12">
        <main class="flex flex-1 flex-col justify-center">
            {{ $slot }}
        </main>
    </div>

    {{-- Rodapé preto: parceria Empreende Brazil × FBS --}}
    <footer class="bg-zinc-950 text-white">
        <div class="h-1 w-full bg-gradient-to-r from-fbs-crimson via-fbs-orange to-fbs-gold"></div>
        <div class="mx-auto flex {{ $__container }} flex-col items-center gap-4 px-4 py-8 text-center">
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-4">
                <span class="rounded-lg bg-white px-4 py-3 shadow-sm">
                    <img src="{{ asset('images/empreende-brazil.png') }}" alt="Empreende Brazil" class="h-12 w-auto sm:h-14">
                </span>
                <span aria-hidden="true" class="text-xl font-light text-white/25">×</span>
                <img src="{{ asset('images/fbs-white.svg') }}" alt="FBS — Fonseca Brasil Serrão Advogados" class="h-8 w-auto sm:h-9">
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
