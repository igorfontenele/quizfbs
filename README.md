# Quiz de Diagnóstico Jurídico — Empreende Brazil 2026

Aplicação web (mobile-first, acesso via QR Code) onde participantes do evento respondem
9 perguntas em 3 eixos e recebem um diagnóstico **VERDE / AMARELO / VERMELHO**, uma cartilha
em PDF e um CTA para o "Café com o Advogado". O diagnóstico também é enviado por e-mail.

**Stack:** Laravel 13 · Livewire 4 · Flux UI Pro 2 · Tailwind CSS 4 · PostgreSQL (prod) / SQLite (dev) · DomPDF · SimpleSoftwareIO QR Code

> Escopo técnico completo: [`ESCOPO_PROJETO_QUIZ_LARAVEL.md`](ESCOPO_PROJETO_QUIZ_LARAVEL.md)

---

## Rotas principais

| Rota | Descrição |
|---|---|
| `GET /` | Landing — destino do QR Code |
| `GET /diagnostico` | Coleta de leads (Livewire) |
| `GET /diagnostico/quiz/{lead}` | Quiz — **URL assinada** |
| `GET /diagnostico/resultado/{resposta}` | Resultado — **URL assinada** |
| `GET /cartilha/{slug}/{resposta}` | Download da cartilha em PDF — **URL assinada** |
| `GET /cafe-com-advogado/{resposta?}` | Registra o clique no CTA e redireciona |
| `GET /qr` | Página com o QR Code pronto para impressão A4 (opcionalmente protegida por `?key=`) |
| `GET /admin` | Painel: leads, estatísticas, respostas — **HTTP Basic Auth** (senha = `ADMIN_PASSWORD`) |
| `GET /admin/export` | Exporta os leads + respostas em CSV (também protegido) |
| `GET /up` | Health check (usado pelo Railway) |

As perguntas, opções, mensagens e cartilhas ficam centralizadas em [`config/quiz.php`](config/quiz.php).
A regra de classificação está em [`app/Services/QuizClassifierService.php`](app/Services/QuizClassifierService.php).

---

## Desenvolvimento local

Pré-requisitos: PHP 8.4, Composer, Node 22+.

```bash
# Credenciais do Flux Pro (uma vez, na máquina)
composer config --global http-basic.composer.fluxui.dev "<email-da-conta-flux>" "<license-key>"

composer install
npm install

cp .env.example .env
php artisan key:generate

# Banco local: SQLite (já é o default no .env.example)
touch database/database.sqlite
php artisan migrate

# Em dois terminais:
php artisan serve          # http://localhost:8000
npm run dev

# E, se quiser testar o e-mail (fila):
php artisan queue:work
```

E-mails em dev vão para o log (`MAIL_MAILER=log`) — veja `storage/logs/laravel.log`.

### Testes

```bash
php artisan test
```

Cobre: classificação (todos os 19 cenários de pontuação + bordas), fluxo completo do quiz
(lead → quiz → resultado), URLs assinadas, geração do PDF, CTA, honeypot, LGPD e a página do QR.

---

## Deploy no Railway

A app sobe via **Dockerfile** (nginx + php-fpm, imagem `serversideup/php`). O `railway.json`
já aponta para ele e configura o health check em `/up`.

### Passo a passo

1. **Crie o projeto no Railway** a partir deste repositório (`Deploy from GitHub repo`).
   O Railway detecta o `Dockerfile` automaticamente.

2. **Adicione um banco PostgreSQL**: no projeto, `+ New` → `Database` → `PostgreSQL`.

3. **Variáveis de ambiente do serviço web** (`Variables`):

   | Variável | Valor |
   |---|---|
   | `APP_NAME` | `Diagnóstico Jurídico` |
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_KEY` | gere com `php artisan key:generate --show` e cole aqui |
   | `APP_URL` | a URL pública do serviço (ex.: `https://seu-app.up.railway.app`) — **importante para as URLs assinadas** |
   | `APP_LOCALE` | `pt_BR` |
   | `DB_CONNECTION` | `pgsql` |
   | `DB_URL` | `${{Postgres.DATABASE_URL}}` (referência ao plugin Postgres) |
   | `QUEUE_CONNECTION` | `database` |
   | `SESSION_DRIVER` | `database` |
   | `CACHE_STORE` | `database` |
   | `CAFE_COM_ADVOGADO_URL` | seu link de WhatsApp ou Calendly |
   | `ADMIN_PASSWORD` | senha do painel `/admin` (HTTP Basic Auth). Vazio = painel desativado |
   | `QR_ACCESS_KEY` | (opcional) protege a rota `/qr` |
   | `COMPOSER_AUTH` | `{"http-basic":{"composer.fluxui.dev":{"username":"<email-flux>","password":"<license-key>"}}}` — necessário para o build instalar o Flux Pro |
   | `MAIL_MAILER` | `resend` (recomendado) — ou `smtp`/`log` |
   | `RESEND_API_KEY` | sua chave da [Resend](https://resend.com) (necessária se `MAIL_MAILER=resend`) |
   | `MAIL_FROM_ADDRESS` | ex.: `diagnostico@fonsecabrasilserrao.com` (o domínio precisa estar verificado na Resend) |

   Dois e-mails são enviados: **(1)** ao preencher o formulário — agradecimento com o logo do FBS;
   **(2)** ao concluir o quiz — o diagnóstico + a cartilha em PDF.

   > As migrations rodam sozinhas no boot do container (`AUTORUN_LARAVEL_MIGRATION=true`),
   > assim como `storage:link` e os caches de config/rotas/views.

4. **Worker da fila (e-mails)** — crie um **segundo serviço** no mesmo projeto, a partir do
   mesmo repositório, e em `Settings → Deploy → Custom Start Command` coloque:

   ```
   php artisan queue:work --tries=3 --max-time=3600 --sleep=3
   ```

   Replique nele as mesmas variáveis de banco/mail (`DB_*`, `MAIL_*`, `APP_KEY`, `QUEUE_CONNECTION=database`).
   Sem esse worker o app funciona normalmente, mas os e-mails ficam parados na fila.

5. **Domínio**: em `Settings → Networking → Generate Domain` (ou aponte seu domínio próprio).
   Atualize `APP_URL` com o domínio final e faça um redeploy.

6. **QR Code para o evento**: acesse `https://SEU_DOMINIO/qr` (com `?key=...` se você definiu
   `QR_ACCESS_KEY`), use o botão **Imprimir** e gere o A4. O QR aponta para a raiz do site.

### Notas

- O `Dockerfile` faz build em 3 estágios: deps PHP (Composer) → assets (Vite/Tailwind/Flux CSS) → runtime.
- O build dos assets precisa do `vendor/` porque `resources/css/app.css` importa `vendor/livewire/flux/dist/flux.css`.
- `bootstrap/app.php` já confia nos proxies do Railway (`trustProxies(at: '*')`), o que mantém a
  detecção de HTTPS e as URLs assinadas funcionando.
- Para mover o quiz para uma versão editável via painel admin, criar a tabela `quiz_perguntas`
  (ver seção opcional do escopo) — hoje as perguntas vivem em `config/quiz.php`.
