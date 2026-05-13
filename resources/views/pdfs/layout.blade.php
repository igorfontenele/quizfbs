<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $cartilha['titulo'] }} — Fonseca Brasil Serrão Advogados</title>
    @php
        $accent = trim($__env->yieldContent('accent')) ?: '#bd213f';
        $ano = optional($gerado_em ?? now())->format('Y');
    @endphp
    <style>
        @page { margin: 24mm 20mm 24mm 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "DejaVu Sans", sans-serif; font-size: 10.5pt; color: #1f2937; line-height: 1.55; }

        /* ---------- Capa ---------- */
        .cover { text-align: center; }
        .cover .brandbar {
            background: #0a0a0a; margin: 0 -20mm; padding: 14mm 20mm; text-align: center;
        }
        .cover .brandbar img { height: 13mm; width: auto; }
        .cover .accentline { height: 4px; background: {{ $accent }}; margin: 0 -20mm; }
        .cover .eyebrow { margin-top: 18mm; letter-spacing: 3px; text-transform: uppercase; font-size: 9pt; color: {{ $accent }}; font-weight: bold; }
        .cover h1 { font-size: 27pt; margin-top: 8mm; color: #0f172a; line-height: 1.15; }
        .cover h2 { font-size: 13pt; font-weight: normal; color: #64748b; margin-top: 6mm; }
        .cover .rule { width: 36mm; height: 3px; background: {{ $accent }}; margin: 9mm auto 0; }
        .cover .meta { margin-top: 16mm; font-size: 10pt; color: #475569; line-height: 1.8; }
        .cover .meta strong { color: #1e293b; }
        .cover .badge { display: inline-block; padding: 4px 14px; border-radius: 999px; font-size: 9pt; font-weight: bold; color: #fff; background: {{ $accent }}; }
        .cover .ip-box {
            margin: 18mm 8mm 0; padding: 5mm 7mm; border: 1px solid #e2e8f0; border-radius: 4mm;
            background: #f8fafc; font-size: 8pt; color: #64748b; line-height: 1.7; text-align: left;
        }
        .cover .ip-box strong { color: #334155; }

        /* ---------- Conteúdo ---------- */
        .content { padding-top: 6mm; }
        h3 { font-size: 13.5pt; color: #0f172a; margin-top: 8mm; margin-bottom: 3mm; padding-bottom: 2mm; border-bottom: 2px solid {{ $accent }}; }
        h4 { font-size: 11pt; color: {{ $accent }}; margin-top: 6mm; margin-bottom: 1.5mm; }
        p { margin-bottom: 3.2mm; text-align: justify; }
        ul { margin: 1mm 0 4mm 7mm; }
        li { margin-bottom: 1.8mm; padding-left: 1mm; }
        .callout {
            border-left: 3px solid {{ $accent }}; background: #f8fafc; padding: 3.5mm 5mm; margin: 4.5mm 0; border-radius: 0 2mm 2mm 0;
        }
        .callout strong { color: {{ $accent }}; }
        .pagebreak { page-break-before: always; }
        table.checklist { width: 100%; border-collapse: collapse; margin: 2mm 0 4mm; }
        table.checklist td { padding: 2.4mm 0; vertical-align: top; border-bottom: 1px solid #eef2f7; font-size: 10pt; }
        table.checklist td.box { width: 7mm; color: {{ $accent }}; font-size: 12pt; }

        /* ---------- Rodapé final do documento (aparece na última página) ---------- */
        .endnote {
            margin-top: 12mm; padding-top: 4mm; border-top: 1px solid #e2e8f0;
            font-size: 8pt; color: #94a3b8; text-align: center; line-height: 1.6;
        }
        .endnote strong { color: #64748b; }
    </style>
</head>
<body>
    {{-- ====== CAPA ====== --}}
    <div class="cover">
        @php($__fbs = public_path('images/fbs-white.png'))
        <div class="brandbar">
            @if (is_file($__fbs))
                <img src="{{ $__fbs }}" alt="Fonseca Brasil Serrão Advogados">
            @else
                <span style="color:#fff;font-size:14pt;font-weight:bold;">FONSECA BRASIL SERRÃO ADVOGADOS</span>
            @endif
        </div>
        <div class="accentline"></div>

        <div class="eyebrow">Diagnóstico Jurídico &nbsp;·&nbsp; Empreende Brazil 2026</div>
        <h1>{{ $cartilha['titulo'] }}</h1>
        <h2>{{ $cartilha['subtitulo'] }}</h2>
        <div class="rule"></div>

        <div class="meta">
            @if ($lead)
                Preparada para <strong>{{ $lead->nome }}</strong>@if($lead->empresa) — {{ $lead->empresa }}@endif<br>
            @endif
            @if (! empty($resultadoLabel))
                Resultado do diagnóstico:
                <span class="badge">{{ $resultadoLabel }}</span>
                @if (isset($pontuacao))
                    &nbsp; {{ $pontuacao }}/{{ $maxPontuacao ?? 18 }} pontos
                @endif
                <br>
            @endif
            Emitida em {{ optional($gerado_em ?? now())->format('d/m/Y') }}
        </div>

        <div class="ip-box">
            <strong>Aviso de propriedade intelectual.</strong> Este material é de autoria e propriedade intelectual de
            <strong>Fonseca Brasil Serrão Advogados</strong>, protegido pela legislação de direitos autorais (Lei nº 9.610/1998).
            Destina-se exclusivamente ao destinatário acima identificado, para uso pessoal e informativo. É vedada a reprodução,
            distribuição ou comercialização, total ou parcial, sem autorização prévia e por escrito. O conteúdo tem caráter
            geral e informativo e não constitui parecer ou consulta jurídica individualizada.
        </div>
    </div>

    <div class="pagebreak"></div>

    {{-- ====== CONTEÚDO ====== --}}
    <div class="content">
        @yield('conteudo')

        <div class="endnote">
            <strong>Fonseca Brasil Serrão Advogados</strong> &nbsp;·&nbsp; © {{ $ano }} &middot; Diagnóstico Jurídico — Empreende Brazil 2026<br>
            Conteúdo de propriedade intelectual. Uso pessoal e intransferível. Reprodução não autorizada.
        </div>
    </div>
</body>
</html>
