@php
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
    $resultadoLabel = $resposta && $resposta->classificacao ? $labels[$resposta->classificacao] : null;
    $pontuacao = $resposta?->pontuacao_total;
    $maxPontuacao = \App\Services\QuizClassifierService::PONTUACAO_MAXIMA;
    $urgente = $resposta && $resposta->classificacao === 'vermelho';
@endphp

@extends('pdfs.layout')

@section('accent', $urgente ? '#dc2626' : '#eab308')

@section('conteudo')
    <h3>1. O que o seu diagnóstico revelou</h3>
    <p>
        @if ($urgente)
            Seu resultado apontou <strong>vulnerabilidades críticas</strong>. Há frentes em que um evento adverso —
            uma fiscalização, a saída de um sócio, um litígio com prestador — pode gerar passivo relevante ou perda de
            valor de forma imediata. A boa notícia: quase tudo aqui é corrigível com ação dirigida nas próximas semanas.
        @else
            Seu resultado apontou <strong>lacunas que ainda não viraram problema</strong>, mas têm potencial de custar caro —
            sobretudo na transição da Reforma Tributária e em pontos de governança societária e contratual. Esta cartilha
            traça uma rota de revisão preventiva por prioridade.
        @endif
    </p>

    <h3>2. Eixo 1 — Reforma Tributária e Fluxo de Caixa</h3>
    <h4>2.1 Projete o impacto do IVA Dual (IBS/CBS)</h4>
    <p>
        Construa um cenário numérico: como ficam preço, margem e capital de giro com as novas alíquotas e a
        não-cumulatividade ampla? Empresas que só "acompanham" o tema costumam ser surpreendidas no fluxo de caixa do
        período de transição.
    </p>
    <h4>2.2 Recupere créditos antes da extinção</h4>
    <p>
        Levante saldos credores de ICMS/IPI (e PIS/COFINS) e desenhe a estratégia de aproveitamento/recuperação enquanto
        o regime atual ainda vige. Crédito não monitorado é dinheiro deixado na mesa.
    </p>
    <h4>2.3 Revise a precificação</h4>
    <p>Repasse as novas cargas para a formação de preço de cada produto/serviço — não no agregado, mas linha a linha.</p>

    <div class="callout">
        <strong>Prazo importa:</strong> a maior parte das oportunidades tributárias da transição tem janela. Quanto antes
        o mapeamento, maior o ganho.
    </div>

    <h3>3. Eixo 2 — Governança Societária e Contratual</h3>
    <ul>
        <li><strong>Contrato Social / Acordo de Sócios:</strong> defina critério de valuation para saída, regras de sucessão, não concorrência e resolução de impasses. Modelo "padrão de cartório" é insuficiente.</li>
        <li><strong>Cláusulas de responsabilidade:</strong> revise limites de responsabilidade, multas e indenizações em contratos com clientes e fornecedores — ao menos nos de maior valor, idealmente em todos.</li>
        <li><strong>Separação patrimonial:</strong> elimine a mistura de despesas pessoa física / pessoa jurídica; estruture governança (ou holding) que reduza o risco de desconsideração da personalidade jurídica.</li>
        <li><strong>Compliance trabalhista:</strong> contratos, jornada, terceirização e PJ-ização — pontos clássicos de passivo.</li>
    </ul>

    <div class="pagebreak"></div>

    <h3>4. Eixo 3 — Propriedade Intelectual e Ativos Digitais</h3>
    <ul>
        <li><strong>Marca:</strong> registre no INPI nas classes em que você atua (não só na principal) e monitore colidências. Domínio + perfil em redes sociais <em>não</em> equivalem a registro de marca.</li>
        <li><strong>Cessão de direitos:</strong> todo contrato com TI, marketing, design ou P&amp;D precisa de cláusula de cessão definitiva da PI à empresa. Sem isso, você pode estar usando algo que juridicamente não é seu.</li>
        <li><strong>Segredo de negócio:</strong> NDAs específicos e política formal para algoritmos, processos, fórmulas e dados estratégicos — não apenas a cláusula genérica do contrato de trabalho.</li>
        <li><strong>LGPD:</strong> mapa de dados, bases legais, contratos com operadores e plano de resposta a incidentes.</li>
    </ul>

    <h3>5. Plano de ação por prioridade</h3>
    <table class="checklist">
        <tr><td class="box">&#9744;</td><td><strong>Imediato (até 30 dias):</strong> mapear saldos credores e cláusulas de risco em contratos vigentes</td></tr>
        <tr><td class="box">&#9744;</td><td><strong>Imediato:</strong> revisar/atualizar Contrato Social e Acordo de Sócios (valuation, sucessão)</td></tr>
        <tr><td class="box">&#9744;</td><td><strong>Curto prazo (30–90 dias):</strong> cenário numérico do IVA Dual e revisão de precificação</td></tr>
        <tr><td class="box">&#9744;</td><td><strong>Curto prazo:</strong> padronizar cláusula de cessão de PI; iniciar registros de marca pendentes</td></tr>
        <tr><td class="box">&#9744;</td><td><strong>Médio prazo (90–180 dias):</strong> programa de LGPD e política de segredo de negócio</td></tr>
        <tr><td class="box">&#9744;</td><td><strong>Médio prazo:</strong> estruturar separação patrimonial / avaliar holding</td></tr>
    </table>

    <h3>6. Próximo passo</h3>
    <p>
        @if ($urgente)
            Recomendamos não adiar. No <strong>Café com o Advogado</strong> identificamos juntos o que precisa de ação
            <strong>esta semana</strong> e o que pode entrar em um cronograma de 90 dias.
        @else
            No <strong>Café com o Advogado</strong> revisamos juntos quais destes pontos têm maior impacto no seu caso e
            montamos um cronograma realista de revisão preventiva.
        @endif
    </p>
@endsection
