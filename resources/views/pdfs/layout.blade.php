<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $cartilha['titulo'] }} — Empreende Brazil 2026</title>
    <style>
        @page { margin: 28mm 20mm 24mm 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5pt; color: #1f2937; line-height: 1.5; }
        .cover { text-align: center; padding-top: 40mm; }
        .cover .eyebrow { letter-spacing: 3px; text-transform: uppercase; font-size: 9pt; color: @yield('accent'); font-weight: bold; }
        .cover h1 { font-size: 26pt; margin-top: 10mm; color: #111827; }
        .cover h2 { font-size: 13pt; font-weight: normal; color: #6b7280; margin-top: 6mm; }
        .cover .meta { margin-top: 24mm; font-size: 10pt; color: #6b7280; }
        .cover .meta strong { color: #374151; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 9pt; font-weight: bold; color: #fff; background: @yield('accent'); }
        h3 { font-size: 14pt; color: #111827; margin-top: 9mm; margin-bottom: 3mm; border-bottom: 2px solid @yield('accent'); padding-bottom: 2mm; }
        h4 { font-size: 11.5pt; color: @yield('accent'); margin-top: 6mm; margin-bottom: 1.5mm; }
        p { margin-bottom: 3mm; text-align: justify; }
        ul { margin: 0 0 4mm 6mm; }
        li { margin-bottom: 1.5mm; }
        .callout { border-left: 3px solid @yield('accent'); background: #f9fafb; padding: 3mm 5mm; margin: 4mm 0; }
        .callout strong { color: @yield('accent'); }
        .pagebreak { page-break-before: always; }
        .footer-note { margin-top: 12mm; font-size: 8.5pt; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 3mm; }
        table.checklist { width: 100%; border-collapse: collapse; margin: 3mm 0; }
        table.checklist td { padding: 2mm 0; vertical-align: top; border-bottom: 1px solid #f3f4f6; font-size: 10pt; }
        table.checklist td.box { width: 8mm; }
    </style>
</head>
<body>
    <div class="cover">
        <div class="eyebrow">Empreende Brazil 2026 &middot; Diagnóstico Jurídico</div>
        <h1>{{ $cartilha['titulo'] }}</h1>
        <h2>{{ $cartilha['subtitulo'] }}</h2>
        <div class="meta">
            @if ($lead)
                Preparada para <strong>{{ $lead->nome }}</strong>@if($lead->empresa) — {{ $lead->empresa }}@endif<br>
            @endif
            @isset($resultadoLabel)
                Resultado do diagnóstico: <span class="badge">{{ $resultadoLabel }}</span>
                @if(isset($pontuacao)) &nbsp; ({{ $pontuacao }}/{{ $maxPontuacao ?? 18 }} pontos)@endif<br>
            @endisset
            Gerada em {{ optional($gerado_em ?? now())->format('d/m/Y') }}
        </div>
    </div>

    <div class="pagebreak"></div>

    @yield('conteudo')

    <div class="footer-note">
        Este material tem caráter informativo e não substitui análise jurídica individualizada.
        Empreende Brazil 2026 — Diagnóstico Jurídico. Documento gerado automaticamente.
    </div>
</body>
</html>
