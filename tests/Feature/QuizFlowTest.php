<?php

namespace Tests\Feature;

use App\Livewire\LeadCapture;
use App\Livewire\QuizResultado;
use App\Livewire\QuizRunner;
use App\Mail\DiagnosticoResultado;
use App\Models\Lead;
use App\Models\QuizResposta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class QuizFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_carrega(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Iniciar Diagnóstico');
    }

    public function test_pagina_de_privacidade_carrega(): void
    {
        $this->get(route('privacidade'))->assertOk()->assertSee('Política de Privacidade');
    }

    public function test_coleta_de_leads_persiste_e_redireciona_para_o_quiz_assinado(): void
    {
        $area = config('quiz.areas_atuacao')[0];

        Livewire::test(LeadCapture::class)
            ->set('nome', 'Maria Silva')
            ->set('empresa', 'Acme Ltda')
            ->set('email', 'maria@acme.com.br')
            ->set('area_atuacao', $area)
            ->set('consentimento_lgpd', true)
            ->call('submit')
            ->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'email' => 'maria@acme.com.br',
            'empresa' => 'Acme Ltda',
            'consentimento_lgpd' => true,
        ]);
    }

    public function test_lead_capture_exige_consentimento_lgpd(): void
    {
        Livewire::test(LeadCapture::class)
            ->set('nome', 'Maria Silva')
            ->set('empresa', 'Acme Ltda')
            ->set('email', 'maria@acme.com.br')
            ->set('area_atuacao', config('quiz.areas_atuacao')[0])
            ->set('consentimento_lgpd', false)
            ->call('submit')
            ->assertHasErrors('consentimento_lgpd');

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_honeypot_descarta_bots(): void
    {
        Livewire::test(LeadCapture::class)
            ->set('nome', 'Bot')
            ->set('empresa', 'Bot Inc')
            ->set('email', 'bot@bot.com')
            ->set('area_atuacao', config('quiz.areas_atuacao')[0])
            ->set('consentimento_lgpd', true)
            ->set('website', 'http://spam.example')
            ->call('submit')
            ->assertRedirect(route('home'));

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_quiz_route_exige_url_assinada(): void
    {
        $lead = Lead::factory()->create();

        $this->get(route('quiz.run', ['lead' => $lead]))->assertForbidden();
        $this->get(URL::signedRoute('quiz.run', ['lead' => $lead]))->assertOk();
    }

    public function test_fluxo_completo_do_quiz_gera_resultado_vermelho_e_envia_email(): void
    {
        Mail::fake();
        $lead = Lead::factory()->create();

        $component = Livewire::test(QuizRunner::class, ['lead' => $lead]);

        // Eixo 1 — todas exposição crítica
        $component->set('respostas.q1', 2)->set('respostas.q2', 2)->set('respostas.q3', 2)
            ->call('proximo')
            ->assertSet('eixoAtual', 2)
            // Eixo 2
            ->set('respostas.q4', 2)->set('respostas.q5', 2)->set('respostas.q6', 1)
            ->call('proximo')
            ->assertSet('eixoAtual', 3)
            // Eixo 3
            ->set('respostas.q7', 2)->set('respostas.q8', 2)->set('respostas.q9', 1)
            ->call('proximo')
            ->assertSet('analisando', true);

        $resposta = QuizResposta::where('lead_id', $lead->id)->firstOrFail();
        $this->assertSame(16, $resposta->pontuacao_total);
        $this->assertSame('vermelho', $resposta->classificacao);

        Mail::assertQueued(DiagnosticoResultado::class, fn ($mail) => $mail->hasTo($lead->email));
    }

    public function test_quiz_nao_avanca_com_eixo_incompleto(): void
    {
        $lead = Lead::factory()->create();

        Livewire::test(QuizRunner::class, ['lead' => $lead])
            ->set('respostas.q1', 0)
            ->call('proximo')
            ->assertHasErrors('eixo')
            ->assertSet('eixoAtual', 1);
    }

    public function test_pagina_de_resultado_renderiza_diagnostico(): void
    {
        $lead = Lead::factory()->create();
        $resposta = QuizResposta::factory()->for($lead)->comPontuacao(3)->create();

        $this->get(URL::signedRoute('resultado.show', ['resposta' => $resposta]))
            ->assertOk()
            ->assertSee('Maturidade Jurídica');

        Livewire::test(QuizResultado::class, ['resposta' => $resposta])
            ->assertSet('diag.classificacao', 'verde');
    }

    public function test_resultado_exige_url_assinada(): void
    {
        $resposta = QuizResposta::factory()->comPontuacao(10)->create();

        $this->get(route('resultado.show', ['resposta' => $resposta]))->assertForbidden();
    }

    public function test_download_da_cartilha_retorna_pdf_e_marca_flag(): void
    {
        $resposta = QuizResposta::factory()->comPontuacao(15)->create(); // vermelho -> gestao-riscos
        $slug = 'cartilha-de-gestao-de-riscos';

        $response = $this->get(URL::signedRoute('cartilha.download', ['slug' => $slug, 'resposta' => $resposta]));

        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('content-type'));
        $this->assertTrue($resposta->fresh()->cartilha_baixada);
    }

    public function test_cartilha_errada_para_a_classificacao_da_404(): void
    {
        $resposta = QuizResposta::factory()->comPontuacao(2)->create(); // verde -> expansao

        $this->get(URL::signedRoute('cartilha.download', [
            'slug' => 'cartilha-de-gestao-de-riscos',
            'resposta' => $resposta,
        ]))->assertNotFound();
    }

    public function test_cta_cafe_com_advogado_marca_clique_e_redireciona(): void
    {
        $resposta = QuizResposta::factory()->comPontuacao(8)->create();

        $this->get(route('cafe-com-advogado', ['resposta' => $resposta]))
            ->assertRedirect(config('quiz.cafe_com_advogado_url'));

        $this->assertTrue($resposta->fresh()->cta_clicado);
    }

    public function test_qr_page_renderiza_svg(): void
    {
        $this->get(route('qr'))
            ->assertOk()
            ->assertSee('<svg', false)
            ->assertSee(route('home'));
    }
}
