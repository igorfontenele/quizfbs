<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>QR Code — Diagnóstico Jurídico | Empreende Brazil 2026</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet" />
    <style>
        :root { --crimson: #bd213f; --orange: #f37521; --gold: #fcb813; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #18181b; background: #fff; }
        .bar { height: 8mm; background: linear-gradient(90deg, var(--crimson) 0%, var(--orange) 55%, var(--gold) 100%); }
        .sheet {
            width: 210mm; min-height: 289mm; margin: 0 auto; padding: 18mm 18mm 14mm;
            display: flex; flex-direction: column; align-items: center; text-align: center;
        }
        .logo-fbs-box { display: inline-block; background: #0a0a0a; padding: 3mm 7mm; border-radius: 3mm; margin-bottom: 5mm; }
        .logo-fbs-box img { height: 9mm; width: auto; display: block; }
        .eyebrow { letter-spacing: .18em; text-transform: uppercase; font-size: 12pt; color: var(--crimson); font-weight: 600; }
        h1 { font-size: 30pt; font-weight: 700; margin-top: 6mm; line-height: 1.15; }
        .lead { font-size: 14pt; color: #52525b; margin-top: 6mm; max-width: 150mm; }
        .qr-box { margin: 12mm 0; padding: 8mm; border: 3px solid var(--crimson); border-radius: 6mm; }
        .qr-box svg { width: 105mm; height: 105mm; display: block; }
        .steps { display: flex; gap: 10mm; margin-top: 2mm; }
        .step { font-size: 12pt; color: #3f3f46; }
        .step b { display: block; font-size: 22pt; color: var(--orange); }
        .url { margin-top: 8mm; font-size: 13pt; color: #71717a; word-break: break-all; }
        .foot { margin-top: auto; padding-top: 10mm; display: flex; align-items: center; gap: 8mm; }
        .foot .emp { height: 16mm; width: auto; }
        .foot .fbs-box { background: #0a0a0a; padding: 3mm 6mm; border-radius: 3mm; display: inline-flex; }
        .foot .fbs-box img { height: 8mm; width: auto; }
        .foot .x { color: #d4d4d8; font-weight: 300; font-size: 16pt; }
        .print-hint { position: fixed; top: 12px; right: 12px; }
        .print-hint button {
            font: inherit; font-size: 12pt; padding: 8px 16px; border-radius: 8px; border: 0;
            background: var(--crimson); color: #fff; cursor: pointer;
        }
        @media print { .print-hint { display: none; } }
        @page { size: A4; margin: 0; }
    </style>
</head>
<body>
    <div class="print-hint"><button onclick="window.print()">Imprimir</button></div>

    <div class="bar"></div>
    <div class="sheet">
        <span class="logo-fbs-box"><img src="{{ asset('images/fbs-white.svg') }}" alt="FBS — Fonseca Brasil Serrão Advogados"></span>
        <div class="eyebrow">Empreende Brazil 2026</div>
        <h1>Faça o Diagnóstico<br>Jurídico da sua Empresa</h1>
        <p class="lead">9 perguntas rápidas em 3 eixos. Em menos de 3 minutos você recebe um diagnóstico e uma cartilha exclusiva.</p>

        <div class="qr-box">{!! $svg !!}</div>

        <div class="steps">
            <div class="step"><b>1</b>Aponte a câmera</div>
            <div class="step"><b>2</b>Acesse o link</div>
            <div class="step"><b>3</b>Responda o quiz</div>
        </div>

        <div class="url">{{ $url }}</div>

        <div class="foot">
            <img class="emp" src="{{ asset('images/empreende-brazil.png') }}" alt="Empreende Brazil">
            <span class="x">×</span>
            <span class="fbs-box"><img src="{{ asset('images/fbs-white.svg') }}" alt="FBS — Fonseca Brasil Serrão Advogados"></span>
        </div>
    </div>
</body>
</html>
