<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Origem padrão dos leads
    |--------------------------------------------------------------------------
    */
    'origem' => env('QUIZ_ORIGEM', 'evento_empreende_2026'),

    /*
    |--------------------------------------------------------------------------
    | Destino do CTA "Café com o Advogado"
    |--------------------------------------------------------------------------
    | Pode ser um link de WhatsApp, Calendly, Google Agenda, etc.
    */
    'cafe_com_advogado_url' => env('CAFE_COM_ADVOGADO_URL', 'https://wa.me/5511999999999'),

    /*
    |--------------------------------------------------------------------------
    | Chave opcional para proteger a rota /qr (página de impressão do QR Code)
    |--------------------------------------------------------------------------
    | Se definida, /qr só abre com ?key=<valor>. Se vazia, fica liberada.
    */
    'qr_access_key' => env('QR_ACCESS_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Credenciais do painel administrativo em /admin (login por sessão)
    |--------------------------------------------------------------------------
    | Se ADMIN_PASSWORD estiver vazia, o painel fica indisponível (404).
    */
    'admin_username' => env('ADMIN_USERNAME', 'admin'),
    'admin_password' => env('ADMIN_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Áreas de atuação oferecidas no formulário de leads
    |--------------------------------------------------------------------------
    */
    'areas_atuacao' => [
        'Indústria',
        'Comércio / Varejo',
        'Serviços',
        'Tecnologia / Software',
        'Saúde',
        'Educação',
        'Construção / Imobiliário',
        'Agronegócio',
        'Alimentos e Bebidas',
        'Logística / Transporte',
        'Financeiro',
        'Marketing / Publicidade',
        'Outro',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sistema de pontuação
    |--------------------------------------------------------------------------
    | 0 = Maturidade / Conformidade
    | 1 = Alerta / Incompletude
    | 2 = Exposição Crítica
    */

    /*
    |--------------------------------------------------------------------------
    | Eixos e perguntas (9 perguntas fixas, 3 por eixo)
    |--------------------------------------------------------------------------
    */
    'eixos' => [
        1 => [
            'nome' => 'Reforma Tributária e Fluxo de Caixa',
            'perguntas' => [
                'q1' => [
                    'titulo' => 'Transição IVA',
                    'enunciado' => 'Sua empresa projetou o impacto do IVA Dual (IBS/CBS) no fluxo de caixa?',
                    'opcoes' => [
                        0 => 'Sim, cenário desenhado.',
                        1 => 'Acompanhando, mas sem projeção numérica.',
                        2 => 'Não iniciamos o mapeamento.',
                    ],
                ],
                'q2' => [
                    'titulo' => 'Créditos Acumulados',
                    'enunciado' => 'Existe plano para o aproveitamento de créditos de ICMS/IPI antes da extinção?',
                    'opcoes' => [
                        0 => 'Sim, plano de recuperação ativo.',
                        1 => 'Sei que tenho créditos, mas sem estratégia de transição.',
                        2 => 'Não sei se possuo créditos ou como recuperá-los.',
                    ],
                ],
                'q3' => [
                    'titulo' => 'Carga Tributária Setorial',
                    'enunciado' => 'Já foi avaliado o impacto das novas alíquotas na precificação do seu produto/serviço?',
                    'opcoes' => [
                        0 => 'Sim, precificação revisada.',
                        1 => 'Em fase de estudo preliminar.',
                        2 => 'Não avaliamos o impacto no preço final.',
                    ],
                ],
            ],
        ],
        2 => [
            'nome' => 'Governança Societária e Contratual',
            'perguntas' => [
                'q4' => [
                    'titulo' => 'Sucessão e Quotas',
                    'enunciado' => 'Os critérios de valuation para saída de sócios estão atualizados no Contrato Social?',
                    'opcoes' => [
                        0 => 'Sim, acordo de sócios e valuation definidos.',
                        1 => 'Contrato padrão (baseado no capital integralizado).',
                        2 => 'Contrato desatualizado ou sem previsão de critérios.',
                    ],
                ],
                'q5' => [
                    'titulo' => 'Responsabilidade Contratual',
                    'enunciado' => 'Há revisão anual de cláusulas de limitação de responsabilidade e multas?',
                    'opcoes' => [
                        0 => 'Sim, em todas as renovações.',
                        1 => 'Apenas em contratos de alto valor.',
                        2 => 'Modelos padrão sem revisão jurídica frequente.',
                    ],
                ],
                'q6' => [
                    'titulo' => 'Proteção Patrimonial',
                    'enunciado' => 'Existe separação clara e institucionalizada entre o patrimônio dos sócios e o da empresa?',
                    'opcoes' => [
                        0 => 'Sim, com holding ou governança rígida.',
                        1 => 'Parcial, mas com misturas eventuais de despesas.',
                        2 => 'Não há separação formal ou estratégica.',
                    ],
                ],
            ],
        ],
        3 => [
            'nome' => 'Propriedade Intelectual e Ativos Digitais',
            'perguntas' => [
                'q7' => [
                    'titulo' => 'Proteção de Marca',
                    'enunciado' => 'A marca está registrada no INPI em todas as classes de atuação e monitorada?',
                    'opcoes' => [
                        0 => 'Sim, registro deferido e monitoramento ativo.',
                        1 => 'Registro apenas na classe principal.',
                        2 => 'Tenho o domínio do site e uso o mesmo nome em redes sociais.',
                    ],
                ],
                'q8' => [
                    'titulo' => 'Cessão de Direitos',
                    'enunciado' => 'Contratos com prestadores (TI/Marketing) garantem a titularidade da PI para a empresa?',
                    'opcoes' => [
                        0 => 'Sim, cláusula de cessão definitiva em todos os contratos.',
                        1 => 'Apenas em contratos de desenvolvimento de software.',
                        2 => 'Não há cláusulas de cessão de direitos autorais/industriais.',
                    ],
                ],
                'q9' => [
                    'titulo' => 'Segredos de Negócio',
                    'enunciado' => 'Existe política formal de confidencialidade (NDA) para algoritmos, processos ou dados estratégicos?',
                    'opcoes' => [
                        0 => 'Sim, com termos específicos e atualizados.',
                        1 => 'Apenas cláusulas genéricas em contratos de trabalho.',
                        2 => 'Não possuímos política de proteção de segredos de negócio.',
                    ],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Faixas de classificação
    |--------------------------------------------------------------------------
    | Pontuação total varia de 0 a 18.
    */
    'classificacoes' => [
        'verde' => [
            'min' => 0,
            'max' => 5,
            'cor_hex' => '#16a34a',
            'flux_variant' => 'success',
            'titulo' => 'Maturidade Jurídica',
            'mensagem' => 'Sua empresa apresenta maturidade jurídica. O foco agora deve ser no Planejamento Sucessório e na Otimização de Lucros.',
            'cta_texto' => 'Retire sua Cartilha de Expansão',
            'cartilha_slug' => 'cartilha-de-expansao',
        ],
        'amarelo' => [
            'min' => 6,
            'max' => 12,
            'cor_hex' => '#eab308',
            'flux_variant' => 'warning',
            'titulo' => 'Atenção: Lacunas Identificadas',
            'mensagem' => 'Existem lacunas que podem gerar custos inesperados em curto prazo, especialmente na transição tributária. Você precisa de uma revisão preventiva.',
            'cta_texto' => 'Retire sua Cartilha de Gestão de Riscos',
            'cartilha_slug' => 'cartilha-de-gestao-de-riscos',
        ],
        'vermelho' => [
            'min' => 13,
            'max' => 18,
            'cor_hex' => '#dc2626',
            'flux_variant' => 'danger',
            'titulo' => 'Atenção Urgente',
            'mensagem' => 'Sua operação possui vulnerabilidades críticas que podem gerar passivos imediatos ou perda de créditos valiosos.',
            'cta_texto' => 'Atenção Urgente: Retire sua Cartilha de Gestão de Riscos',
            'cartilha_slug' => 'cartilha-de-gestao-de-riscos',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fechamento comum a todas as classificações
    |--------------------------------------------------------------------------
    */
    'fechamento' => 'Deseja aprofundar esses pontos? Vamos nos reunir agora com o Café com o Advogado?',

    /*
    |--------------------------------------------------------------------------
    | Cartilhas (PDFs gerados via DomPDF)
    |--------------------------------------------------------------------------
    */
    'cartilhas' => [
        'cartilha-de-expansao' => [
            'titulo' => 'Cartilha de Expansão',
            'subtitulo' => 'Planejamento Sucessório e Otimização de Lucros',
            'view' => 'pdfs.cartilha-expansao',
            'arquivo' => 'cartilha-de-expansao.pdf',
        ],
        'cartilha-de-gestao-de-riscos' => [
            'titulo' => 'Cartilha de Gestão de Riscos',
            'subtitulo' => 'Blindagem Tributária, Societária e de Propriedade Intelectual',
            'view' => 'pdfs.cartilha-gestao-riscos',
            'arquivo' => 'cartilha-de-gestao-de-riscos.pdf',
        ],
    ],

];
