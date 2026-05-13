@php
    $labels = ['verde' => 'Verde', 'amarelo' => 'Amarelo', 'vermelho' => 'Vermelho'];
    $resultadoLabel = $resposta && $resposta->classificacao ? $labels[$resposta->classificacao] : null;
    $pontuacao = $resposta?->pontuacao_total;
    $maxPontuacao = \App\Services\QuizClassifierService::PONTUACAO_MAXIMA;
@endphp

@extends('pdfs.layout')

@section('accent', '#16a34a')

@section('conteudo')
    <h3>1. Você chegou ao próximo nível. E agora?</h3>
    <p>
        Seu diagnóstico indicou <strong>maturidade jurídica</strong>: contratos revisados, governança organizada e
        ativos minimamente protegidos. Nesse estágio, a alavanca de valor deixa de ser "apagar incêndios" e passa a ser
        <strong>planejamento</strong> — sucessório, tributário e de expansão. Esta cartilha reúne os movimentos que empresas
        nesse patamar costumam priorizar.
    </p>

    <h3>2. Planejamento Sucessório e Societário</h3>

    <h4>2.1 Acordo de Sócios vivo</h4>
    <p>
        Mais importante que o Contrato Social é o <strong>Acordo de Sócios</strong>: ele define regras de entrada e saída,
        tag along / drag along, não concorrência, deadlock e — crucialmente — o <strong>critério de valuation</strong> em
        caso de saída, morte ou divórcio de um sócio. Reveja-o a cada rodada de crescimento relevante.
    </p>

    <h4>2.2 Holding patrimonial e familiar</h4>
    <p>
        Estruturar uma holding pode (a) organizar a sucessão com doação de quotas com reserva de usufruto, (b) reduzir
        custos de inventário e ITCMD com planejamento antecipado e (c) blindar o patrimônio pessoal do risco operacional.
        Avalie com um especialista a relação custo/benefício para o seu caso.
    </p>

    <div class="callout">
        <strong>Sinal de alerta:</strong> se a sucessão depende hoje de "bom senso da família", você não tem um plano —
        tem um risco. Formalize antes que o evento aconteça.
    </div>

    <h3>3. Otimização Tributária Lícita</h3>
    <ul>
        <li><strong>Revisão de regime:</strong> Simples, Presumido ou Real — a escolha ótima muda com faturamento, margem e folha. Reavalie anualmente, sobretudo na transição da Reforma Tributária.</li>
        <li><strong>Aproveitamento de créditos:</strong> mapeie créditos de ICMS/IPI/PIS/COFINS ainda existentes e desenhe a estratégia de recuperação antes da extinção dos tributos.</li>
        <li><strong>Distribuição de lucros:</strong> isenta de IR para a pessoa física hoje — estruture pró-labore e distribuição de forma defensável e documentada.</li>
        <li><strong>Incentivos setoriais e regionais:</strong> verifique benefícios estaduais/municipais aplicáveis ao seu CNAE.</li>
    </ul>

    <h3>4. Blindagem dos Ativos Intangíveis</h3>
    <p>
        Em empresas maduras, boa parte do valor está em marca, software, base de clientes e know-how. Garanta:
    </p>
    <ul>
        <li>Registro da marca no INPI em <strong>todas as classes</strong> de atuação + monitoramento de colidências;</li>
        <li>Cláusula de <strong>cessão de direitos</strong> de PI em todo contrato com prestador (TI, design, marketing, P&D);</li>
        <li>Política de <strong>confidencialidade (NDA)</strong> e de segredo de negócio para algoritmos, processos e dados estratégicos;</li>
        <li>Conformidade com a <strong>LGPD</strong> como ativo de confiança (mapa de dados, bases legais, contratos com operadores).</li>
    </ul>

    <div class="pagebreak"></div>

    <h3>5. Checklist de Expansão (12 meses)</h3>
    <table class="checklist">
        <tr><td class="box">&#9744;</td><td>Acordo de Sócios revisado com cláusula de valuation atualizada</td></tr>
        <tr><td class="box">&#9744;</td><td>Estudo de holding patrimonial/familiar concluído (com decisão go / no-go)</td></tr>
        <tr><td class="box">&#9744;</td><td>Reavaliação de regime tributário frente à Reforma (IBS/CBS)</td></tr>
        <tr><td class="box">&#9744;</td><td>Plano de recuperação de créditos acumulados em execução</td></tr>
        <tr><td class="box">&#9744;</td><td>Marca registrada em todas as classes + monitoramento ativo</td></tr>
        <tr><td class="box">&#9744;</td><td>Cláusula de cessão de PI padronizada em todos os contratos com fornecedores</td></tr>
        <tr><td class="box">&#9744;</td><td>Programa de LGPD implementado e auditado</td></tr>
        <tr><td class="box">&#9744;</td><td>Due diligence interna (legal/contábil) pronta para captação ou M&amp;A</td></tr>
    </table>

    <h3>6. Próximo passo</h3>
    <p>
        Estes pontos rendem mais quando priorizados a partir do <strong>seu</strong> cenário. No
        <strong>Café com o Advogado</strong> revisamos juntos o que tem maior impacto e menor custo para a sua empresa
        nos próximos meses.
    </p>
@endsection
