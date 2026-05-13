<?php

namespace Tests\Feature;

use App\Livewire\AdminLeads;
use App\Livewire\AdminLogin;
use App\Models\Lead;
use App\Models\QuizResposta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function withAdminCreds(string $user = 'admin', string $pass = 'segredo'): void
    {
        config()->set('quiz.admin_username', $user);
        config()->set('quiz.admin_password', $pass);
        RateLimiter::clear('admin-login:127.0.0.1');
    }

    public function test_painel_redireciona_pro_login_sem_sessao(): void
    {
        $this->withAdminCreds();

        $this->get(route('admin.leads'))->assertRedirect(route('admin.login'));
    }

    public function test_painel_indisponivel_sem_senha_configurada(): void
    {
        config()->set('quiz.admin_password', null);

        $this->get(route('admin.leads'))->assertNotFound();
        $this->get(route('admin.login'))->assertNotFound();
    }

    public function test_login_renderiza_formulario(): void
    {
        $this->withAdminCreds();

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Painel do Diagnóstico')
            ->assertSee('Entrar');
    }

    public function test_login_recusa_credenciais_erradas(): void
    {
        $this->withAdminCreds('admin', 'certa');

        Livewire::test(AdminLogin::class)
            ->set('usuario', 'admin')
            ->set('senha', 'errada')
            ->call('submit')
            ->assertHasErrors('usuario');

        $this->assertFalse(session()->get('admin_authenticated', false));
    }

    public function test_login_aceita_credenciais_corretas_e_redireciona(): void
    {
        $this->withAdminCreds('admin@fbs', 's3nh@-Forte');

        Livewire::test(AdminLogin::class)
            ->set('usuario', 'admin@fbs')
            ->set('senha', 's3nh@-Forte')
            ->call('submit')
            ->assertRedirect(route('admin.leads'));

        $this->assertTrue(session()->get('admin_authenticated'));
    }

    public function test_logout_limpa_sessao(): void
    {
        $this->withAdminCreds();

        $this->withSession(['admin_authenticated' => true])
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_painel_acessivel_com_sessao_autenticada(): void
    {
        $this->withAdminCreds();
        Lead::factory()->create(['nome' => 'Fulano de Tal']);

        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.leads'))
            ->assertOk()
            ->assertSee('Painel')
            ->assertSee('Fulano de Tal');
    }

    public function test_export_csv_exige_sessao(): void
    {
        $this->withAdminCreds();
        Lead::factory()->has(QuizResposta::factory()->comPontuacao(8), 'quizResposta')->create(['nome' => 'Cliente Exportado']);

        $this->get(route('admin.export'))->assertRedirect(route('admin.login'));

        $response = $this->withSession(['admin_authenticated' => true])->get(route('admin.export'));
        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Cliente Exportado', $content);
        $this->assertStringContainsString('amarelo', $content);
    }

    public function test_componente_filtra_por_classificacao(): void
    {
        Lead::factory()->has(QuizResposta::factory()->comPontuacao(2), 'quizResposta')->create(['nome' => 'Verde Lead']);
        Lead::factory()->has(QuizResposta::factory()->comPontuacao(16), 'quizResposta')->create(['nome' => 'Vermelho Lead']);

        Livewire::test(AdminLeads::class)
            ->set('classificacao', 'vermelho')
            ->assertSee('Vermelho Lead')
            ->assertDontSee('Verde Lead');
    }

    public function test_componente_busca_por_texto(): void
    {
        Lead::factory()->create(['nome' => 'Maria Aparecida', 'empresa' => 'Padaria Pão Quente']);
        Lead::factory()->create(['nome' => 'João Carlos', 'empresa' => 'Construtora Alfa']);

        Livewire::test(AdminLeads::class)
            ->set('search', 'pão quente')
            ->assertSee('Maria Aparecida')
            ->assertDontSee('João Carlos');
    }
}
