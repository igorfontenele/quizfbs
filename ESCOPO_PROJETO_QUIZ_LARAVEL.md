# 📋 ESCOPO TÉCNICO — Quiz de Diagnóstico Jurídico

**Projeto:** Quiz de Diagnóstico Jurídico — Empreende Brazil 2026
**Stack:** Laravel 12 + Livewire 3 + Flux UI Pro + Tailwind CSS 4
**Tipo:** Aplicação web responsiva (foco mobile/tablet via QR Code)

---

## 1. Visão Geral

Aplicação web para captura de leads em evento, onde participantes respondem um quiz de 9 perguntas distribuídas em 3 eixos temáticos. Ao final, recebem um diagnóstico classificado em VERDE, AMARELO ou VERMELHO, com uma cartilha em PDF correspondente e um CTA para agendar um "Café com o Advogado".

**Objetivo de negócio:** Identificar o nível de maturidade e exposição a riscos das empresas participantes do Empreende Brazil 2026, gerando leads qualificados.

---

## 2. Stack Técnica Completa

| Camada | Tecnologia | Versão | Função |
|---|---|---|---|
| Backend | Laravel | 12.x | Framework principal |
| Frontend reativo | Livewire | 3.x | Componentes dinâmicos |
| UI Kit | Flux UI Pro | 2.x | Componentes visuais |
| CSS | Tailwind CSS | 4.x | Estilização |
| Banco | MySQL ou PostgreSQL | 8.x / 16.x | Persistência |
| PDF | DomPDF (`barryvdh/laravel-dompdf`) | última | Geração de cartilhas |
| QR Code | `simplesoftwareio/simple-qrcode` | última | QR Code para o evento |
| E-mail | Laravel Mail + Mailgun/SES | — | Envio do diagnóstico |
| Fonte | Inter (Bunny Fonts) | — | Recomendada pelo Flux |

---

## 3. Fluxo do Usuário (User Journey)

```
1. Participante escaneia QR Code no evento
   ↓
2. Landing page com identidade do evento e botão "Iniciar Diagnóstico"
   ↓
3. Tela de Coleta de Leads (Nome, Empresa, E-mail, Área de Atuação)
   ↓
4. Quiz iniciado — Eixo 1 (Q1, Q2, Q3) com barra de progresso
   ↓
5. Eixo 2 (Q4, Q5, Q6) — barra avança
   ↓
6. Eixo 3 (Q7, Q8, Q9) — barra completa
   ↓
7. Tela de Loading curta ("Analisando suas respostas...")
   ↓
8. Tela de Resultado: cor (Verde/Amarelo/Vermelho) + mensagem + CTA
   ↓
9. Download da Cartilha PDF + botão "Café com o Advogado"
   ↓
10. E-mail automático com o diagnóstico e a cartilha
```

---

## 4. Modelagem de Dados (Schema do Banco)

### Tabela: `leads`
| Campo | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| nome | string(150) | Nome do participante |
| empresa | string(150) | Razão social/nome da empresa |
| email | string(180) | E-mail (validado) |
| area_atuacao | string(120) | Setor/segmento |
| origem | string(50) | "evento_empreende_2026" (default) |
| ip | string(45) | IP de origem |
| user_agent | text | Para análise mobile/tablet |
| created_at / updated_at | timestamp | — |

### Tabela: `quiz_respostas`
| Campo | Tipo | Descrição |
|---|---|---|
| id | bigint PK | — |
| lead_id | FK → leads.id | — |
| q1 | tinyint (0-2) | Resposta da Q1 |
| q2 | tinyint (0-2) | Resposta da Q2 |
| q3 | tinyint (0-2) | Resposta da Q3 |
| q4 | tinyint (0-2) | Resposta da Q4 |
| q5 | tinyint (0-2) | Resposta da Q5 |
| q6 | tinyint (0-2) | Resposta da Q6 |
| q7 | tinyint (0-2) | Resposta da Q7 |
| q8 | tinyint (0-2) | Resposta da Q8 |
| q9 | tinyint (0-2) | Resposta da Q9 |
| pontuacao_total | tinyint | Soma (0-18) |
| classificacao | enum | `verde`, `amarelo`, `vermelho` |
| cartilha_baixada | boolean | Flag de download |
| cta_clicado | boolean | Flag do "Café com o Advogado" |
| created_at / updated_at | timestamp | — |

> **Decisão de design:** Como o quiz tem perguntas fixas (9 questões pré-definidas), opto por colunas explícitas (q1–q9) em vez de uma tabela polimórfica. Mais simples, mais rápido para consultas e relatórios, sem perda de flexibilidade dado o escopo.

### (Opcional) Tabela: `quiz_perguntas`
Se quiser deixar as perguntas editáveis via painel admin no futuro, criar tabela `quiz_perguntas` com (id, eixo, ordem, enunciado, opcao_a, opcao_b, opcao_c). **Recomendado deixar para v2** — na v1 as perguntas ficam num arquivo de config (`config/quiz.php`).

---

## 5. Conteúdo do Quiz (config/quiz.php)

### Sistema de Pontuação
- **0 pontos** = Maturidade/Conformidade (Resposta A)
- **1 ponto** = Alerta/Incompletude (Resposta B)
- **2 pontos** = Exposição Crítica (Resposta C)

### Eixo 1 — Reforma Tributária e Fluxo de Caixa
- **Q1 (Transição IVA):** Sua empresa projetou o impacto do IVA Dual (IBS/CBS) no fluxo de caixa?
  - (0) Sim, cenário desenhado.
  - (1) Acompanhando, mas sem projeção numérica.
  - (2) Não iniciamos o mapeamento.
- **Q2 (Créditos Acumulados):** Existe plano para o aproveitamento de créditos de ICMS/IPI antes da extinção?
  - (0) Sim, plano de recuperação ativo.
  - (1) Sei que tenho créditos, mas sem estratégia de transição.
  - (2) Não sei se possuo créditos ou como recuperá-los.
- **Q3 (Carga Tributária Setorial):** Já foi avaliado o impacto das novas alíquotas na precificação do seu produto/serviço?
  - (0) Sim, precificação revisada.
  - (1) Em fase de estudo preliminar.
  - (2) Não avaliamos o impacto no preço final.

### Eixo 2 — Governança Societária e Contratual
- **Q4 (Sucessão e Quotas):** Os critérios de valuation para saída de sócios estão atualizados no Contrato Social?
  - (0) Sim, acordo de sócios e valuation definidos.
  - (1) Contrato padrão (baseado no capital integralizado).
  - (2) Contrato desatualizado ou sem previsão de critérios.
- **Q5 (Responsabilidade Contratual):** Há revisão anual de cláusulas de limitação de responsabilidade e multas?
  - (0) Sim, em todas as renovações.
  - (1) Apenas em contratos de alto valor.
  - (2) Modelos padrão sem revisão jurídica frequente.
- **Q6 (Proteção Patrimonial):** Existe separação clara e institucionalizada entre o patrimônio dos sócios e o da empresa?
  - (0) Sim, com holding ou governança rígida.
  - (1) Parcial, mas com misturas eventuais de despesas.
  - (2) Não há separação formal ou estratégica.

### Eixo 3 — Propriedade Intelectual e Ativos Digitais
- **Q7 (Proteção de Marca):** A marca está registrada no INPI em todas as classes de atuação e monitorada?
  - (0) Sim, registro deferido e monitoramento ativo.
  - (1) Registro apenas na classe principal.
  - (2) Tenho o domínio do site e uso o mesmo nome em redes sociais.
- **Q8 (Cessão de Direitos):** Contratos com prestadores (TI/Marketing) garantem a titularidade da PI para a empresa?
  - (0) Sim, cláusula de cessão definitiva em todos os contratos.
  - (1) Apenas em contratos de desenvolvimento de software.
  - (2) Não há cláusulas de cessão de direitos autorais/industriais.
- **Q9 (Segredos de Negócio):** Existe política formal de confidencialidade (NDA) para algoritmos, processos ou dados estratégicos?
  - (0) Sim, com termos específicos e atualizados.
  - (1) Apenas cláusulas genéricas em contratos de trabalho.
  - (2) Não possuímos política de proteção de segredos de negócio.

---

## 6. Lógica de Classificação (Backend)

```php
// app/Services/QuizClassifierService.php
public function classify(int $total): array
{
    return match(true) {
        $total >= 0  && $total <= 5  => [
            'classificacao' => 'verde',
            'cor_hex'       => '#16a34a',
            'titulo'        => 'Maturidade Jurídica',
            'mensagem'      => 'Sua empresa apresenta maturidade jurídica. O foco agora deve ser no Planejamento Sucessório e na Otimização de Lucros.',
            'cta_texto'     => 'Retire sua Cartilha de Expansão',
            'cartilha_slug' => 'cartilha-de-expansao',
        ],
        $total >= 6  && $total <= 12 => [
            'classificacao' => 'amarelo',
            'cor_hex'       => '#eab308',
            'titulo'        => 'Atenção: Lacunas Identificadas',
            'mensagem'      => 'Existem lacunas que podem gerar custos inesperados em curto prazo, especialmente na transição tributária. Você precisa de uma revisão preventiva.',
            'cta_texto'     => 'Retire sua Cartilha de Gestão de Riscos',
            'cartilha_slug' => 'cartilha-de-gestao-de-riscos',
        ],
        $total >= 13 && $total <= 18 => [
            'classificacao' => 'vermelho',
            'cor_hex'       => '#dc2626',
            'titulo'        => 'Atenção Urgente',
            'mensagem'      => 'Sua operação possui vulnerabilidades críticas que podem gerar passivos imediatos ou perda de créditos valiosos.',
            'cta_texto'     => 'Atenção Urgente: Retire sua Cartilha de Gestão de Riscos',
            'cartilha_slug' => 'cartilha-de-gestao-de-riscos',
        ],
    };
}
```

**Fechamento comum (todas as classificações):**
> "Deseja aprofundar esses pontos? Vamos nos reunir agora com o Café com o Advogado?"

---

## 7. Estrutura de Arquivos do Projeto

```
app/
├── Livewire/
│   ├── LeadCapture.php          # Form inicial
│   ├── QuizRunner.php           # Quiz com state machine (eixo atual, respostas)
│   └── QuizResultado.php        # Tela final com cor/mensagem/CTA
├── Models/
│   ├── Lead.php
│   └── QuizResposta.php
├── Services/
│   ├── QuizClassifierService.php
│   └── QuizMailService.php      # Envia e-mail com PDF anexo
├── Mail/
│   └── DiagnosticoResultado.php
└── Http/
    └── Controllers/
        ├── QrCodeController.php
        └── CartilhaController.php

resources/
├── views/
│   ├── components/
│   │   ├── layouts/
│   │   │   └── app.blade.php    # Layout principal com @fluxAppearance/@fluxScripts
│   │   └── quiz/
│   │       ├── progress.blade.php
│   │       ├── pergunta-card.blade.php
│   │       └── resultado-card.blade.php
│   ├── livewire/
│   │   ├── lead-capture.blade.php
│   │   ├── quiz-runner.blade.php
│   │   └── quiz-resultado.blade.php
│   ├── emails/
│   │   └── diagnostico.blade.php
│   ├── pdfs/
│   │   ├── cartilha-expansao.blade.php
│   │   └── cartilha-gestao-riscos.blade.php
│   └── welcome.blade.php        # Landing inicial

config/
└── quiz.php                     # Perguntas, opções e mensagens centralizadas

routes/
└── web.php

database/
└── migrations/
    ├── xxxx_create_leads_table.php
    └── xxxx_create_quiz_respostas_table.php

storage/app/public/
└── cartilhas/                   # PDFs estáticos das cartilhas (opcional)
```

---

## 8. Rotas (routes/web.php)

| Método | Rota | Controller/Component | Descrição |
|---|---|---|---|
| GET | `/` | `welcome` view | Landing — abre via QR Code |
| GET | `/diagnostico` | `LeadCapture` (Livewire) | Form de coleta de leads |
| GET | `/diagnostico/quiz/{lead}` | `QuizRunner` (Livewire) | Quiz em si (signed URL) |
| GET | `/diagnostico/resultado/{resposta}` | `QuizResultado` (Livewire) | Tela final (signed URL) |
| GET | `/cartilha/{slug}/{resposta}` | `CartilhaController@download` | Download PDF |
| GET | `/cafe-com-advogado` | redirect → WhatsApp/Calendly | CTA final |
| GET | `/qr` | `QrCodeController@show` | Página admin com QR code para imprimir |

> **Importante:** usar `signed routes` (URL::signedRoute) para evitar que alguém pule direto pro resultado sem responder o quiz.

---

## 9. Componentes Flux UI Pro a Utilizar

| Componente Flux | Onde |
|---|---|
| `<flux:card>` | Container das perguntas |
| `<flux:radio.group>` + `<flux:radio>` | Opções de resposta (A/B/C) |
| `<flux:input>` | Form de leads (Nome, Empresa, E-mail) |
| `<flux:select>` | Área de atuação |
| `<flux:button>` | Botões "Próximo", "Finalizar", CTA |
| `<flux:heading>` + `<flux:subheading>` | Títulos e subtítulos |
| `<flux:badge>` | Indicador de eixo atual |
| `<flux:separator>` | Divisor entre eixos |
| `<flux:callout>` | Tela de resultado (com cor variant: success/warning/danger) |
| `<flux:icon.*>` | Ícones (heroicons já vêm no Flux) |

**Barra de progresso:** o Flux Pro tem componente próprio ou usar custom Tailwind. Sugiro custom simples (3 segmentos, um por eixo) com `<div>` + classes Tailwind, mais alinhado ao branding do evento.

---

## 10. Requisitos de UI/UX (do documento original)

### ✅ Barra de Progresso
- 3 segmentos visuais, um por eixo
- Mostra "Eixo 1 de 3" + nome do eixo atual
- Animação suave ao avançar (Alpine.js transitions)

### ✅ Responsividade Mobile-First
- O quiz será respondido principalmente em **celulares/tablets** durante o evento
- Botões com `min-h-12` (44px+) para área de toque confortável
- Cards full-width em mobile, max-w-2xl em desktop
- Testar em viewport 360px (Android comum) e 768px (iPad)

### ✅ QR Code
- Rota `/qr` (protegida por auth ou simplesmente unguessable) que renderiza um QR code grande, pronto para impressão A4
- QR aponta para a URL pública do diagnóstico (ex: `https://seudominio.com.br/`)

### ✅ Cartilhas vinculadas automaticamente
- **VERDE** → Cartilha de Expansão
- **AMARELO** → Cartilha de Gestão de Riscos
- **VERMELHO** → Cartilha de Gestão de Riscos
- Download via `CartilhaController` que valida o `resposta_id` e a classificação antes de servir o PDF

### ✅ Coleta de Leads ANTES do resultado
- A tela de coleta vem **antes** do quiz começar (ou pode ser após o quiz e antes do resultado — definir)
- **Recomendação:** colocar **antes do quiz** para garantir captura mesmo se o lead desistir no meio
- Campos obrigatórios: Nome, Empresa, E-mail, Área de Atuação
- Validação Livewire em tempo real

---

## 11. Funcionalidades Extras Recomendadas (Pro-grade)

Estas não estão no documento original mas elevam o produto:

1. **Envio de e-mail automático** com o resultado + cartilha em PDF anexa (job em fila para não travar UX)
2. **Dashboard admin** simples (rota `/admin`, auth Laravel padrão) listando:
   - Total de leads
   - Distribuição por classificação (donut chart com Flux Pro charts)
   - Exportar CSV/Excel de leads
3. **Anti-duplicação:** se mesmo e-mail responder de novo no mesmo dia, mostrar resultado anterior ou criar nova entrada (definir regra)
4. **Modo "kiosk"** para tablets do evento: após o usuário ver o resultado, botão "Próximo participante" que reinicia o fluxo sem fechar o navegador
5. **Analytics:** integrar Google Analytics 4 ou Plausible para medir taxa de conclusão por eixo
6. **Honeypot anti-bot** no form de leads (campo escondido que bots preenchem)

---

## 12. Critérios de Aceitação (Definition of Done)

- [ ] Usuário consegue completar o fluxo inteiro em < 3 minutos
- [ ] Funciona em iPhone SE (375px), Android comum (360px), iPad (768px) e desktop
- [ ] Dark mode funcional (Flux já entrega isso)
- [ ] Pontuação calculada corretamente em todos os 19 cenários possíveis (0 a 18)
- [ ] Cada classificação puxa a cartilha correta
- [ ] E-mail é enviado em até 30s após finalização
- [ ] Lead é persistido no banco mesmo se o usuário abandonar no meio do quiz
- [ ] QR Code gera e leva ao link correto
- [ ] LGPD: checkbox de consentimento no form de leads + link para política de privacidade
- [ ] Lighthouse mobile: Performance ≥ 90, Accessibility ≥ 95

---

## 13. Roadmap de Desenvolvimento (Sugerido para o Claude Code)

### Fase 1 — Setup (estimativa: 30 min)
1. Criar projeto: `laravel new quiz-empreende --git`
2. Instalar Livewire: `composer require livewire/livewire`
3. Instalar Flux Pro: `composer require livewire/flux-pro` + ativar licença
4. Configurar Tailwind 4 + fonte Inter (Bunny Fonts)
5. Configurar `.env` (DB, MAIL)

### Fase 2 — Modelagem (estimativa: 20 min)
6. Criar migrations `leads` e `quiz_respostas`
7. Criar models com relacionamentos (`Lead hasOne QuizResposta`)
8. Criar `config/quiz.php` com todas as perguntas/opções/mensagens
9. Rodar `php artisan migrate`

### Fase 3 — Lógica de Negócio (estimativa: 30 min)
10. Criar `QuizClassifierService` com a regra de classificação
11. Criar testes unitários do serviço (bordas: 0, 5, 6, 12, 13, 18)

### Fase 4 — UI / Livewire (estimativa: 2-3h)
12. Layout principal `app.blade.php` com `@fluxAppearance` e `@fluxScripts`
13. Landing `welcome.blade.php` com identidade do evento + botão "Iniciar"
14. Componente Livewire `LeadCapture` com validação
15. Componente Livewire `QuizRunner` com state machine (eixo atual, respostas em array)
16. Componente Blade `progress.blade.php` (barra de progresso por eixo)
17. Componente Livewire `QuizResultado` com Flux callout colorido + CTA

### Fase 5 — PDFs e E-mail (estimativa: 1h)
18. Instalar DomPDF
19. Criar templates Blade para as 2 cartilhas
20. Criar `CartilhaController@download`
21. Criar `Mail/DiagnosticoResultado` + view do e-mail
22. Configurar fila (database driver) para envio assíncrono

### Fase 6 — QR Code e Polish (estimativa: 30 min)
23. Instalar `simplesoftwareio/simple-qrcode`
24. Criar `QrCodeController@show` com QR pronto para impressão
25. Ajustes finais de responsividade, dark mode, animações

### Fase 7 — Testes e Deploy (estimativa: 1h)
26. Feature tests: fluxo completo (lead → quiz → resultado → PDF)
27. Teste manual em dispositivos reais
28. Deploy (Forge / Cloud / Render)

**Total estimado: 6-8 horas de trabalho com Claude Code**

---

## 14. Prompt Inicial Sugerido para o Claude Code

```
Estou criando um quiz de diagnóstico jurídico para um evento corporativo. 
Stack: Laravel 12 + Livewire 3 + Flux UI Pro + Tailwind 4 + MySQL.

O escopo completo está em ESCOPO_PROJETO_QUIZ_LARAVEL.md (já anexado a este projeto).

Vamos começar pela Fase 1 (Setup). Por favor:
1. Crie a estrutura inicial do projeto Laravel
2. Instale Livewire e Flux Pro (vou fornecer minha licença quando você pedir)
3. Configure Tailwind 4 com a fonte Inter
4. Configure o .env para SQLite localmente (dev rápido)
5. Confirme que `php artisan serve` está rodando e mostra a tela welcome

Depois disso, paramos e revisamos antes de seguir para a Fase 2.
```

---

## 15. Considerações Finais e Riscos

| Risco | Mitigação |
|---|---|
| Flux Pro requer licença paga | Adquirir em fluxui.dev/pricing antes de começar |
| E-mail não chega (spam) | Usar SPF/DKIM no domínio + provedor confiável (Mailgun/SES) |
| Quiz abandonado no meio | Lead já salvo na Fase 2 — não perde captura |
| Banco crescendo no evento | Para 500-1000 leads não há problema; usar índice em `email` |
| QR Code mal impresso | Testar antes — gerar em PNG 1000x1000px+ |
| Internet ruim no evento | App funciona com latência alta, mas considerar PWA/offline em v2 |

---

**Documento gerado em:** 12/05/2026
**Autor:** Escopo técnico estruturado para uso com Claude Code
**Versão:** 1.0
