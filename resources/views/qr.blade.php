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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #18181b; background: #fff; }
        .sheet {
            width: 210mm; min-height: 297mm; margin: 0 auto; padding: 24mm 18mm;
            display: flex; flex-direction: column; align-items: center; text-align: center;
        }
        .eyebrow { letter-spacing: .18em; text-transform: uppercase; font-size: 12pt; color: #1d54f5; font-weight: 600; }
        h1 { font-size: 30pt; font-weight: 700; margin-top: 8mm; line-height: 1.15; }
        .lead { font-size: 14pt; color: #52525b; margin-top: 6mm; max-width: 150mm; }
        .qr-box { margin: 16mm 0; padding: 8mm; border: 2px solid #e4e4e7; border-radius: 6mm; }
        .qr-box svg { width: 110mm; height: 110mm; display: block; }
        .steps { display: flex; gap: 8mm; margin-top: 4mm; }
        .step { font-size: 12pt; color: #3f3f46; }
        .step b { display: block; font-size: 22pt; color: #1d54f5; }
        .url { margin-top: 10mm; font-size: 13pt; color: #71717a; word-break: break-all; }
        .foot { margin-top: auto; padding-top: 14mm; font-size: 11pt; color: #a1a1aa; }
        .print-hint { position: fixed; top: 12px; right: 12px; }
        .print-hint button {
            font: inherit; font-size: 12pt; padding: 8px 16px; border-radius: 8px; border: 0;
            background: #1d54f5; color: #fff; cursor: pointer;
        }
        @media print { .print-hint { display: none; } .sheet { padding: 18mm; } }
        @page { size: A4; margin: 0; }
    </style>
</head>
<body>
    <div class="print-hint"><button onclick="window.print()">Imprimir</button></div>

    <div class="sheet">
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

        <div class="foot">Diagnóstico Jurídico &middot; Empreende Brazil 2026</div>
    </div>
</body>
</html>
