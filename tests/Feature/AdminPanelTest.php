<?php

namespace Tests\Feature;

use App\Livewire\AdminLeads;
use App\Models\Lead;
use App\Models\QuizResposta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function withAdminPassword(string $password = 'segredo'): void
    {
        config()->set('quiz.admin_password', $password);
    }

    public function test_admin_exige_autenticacao(): void
    {
        $this->withAdminPassword();

        $this->get(route('admin.leads'))->assertUnauthorized();
        $this->withBasicAuth('admin', 'errado')->get(route('admin.leads'))->assertUnauthorized();
    }

    public function test_admin_indisponivel_sem_senha_configurada(): void
    {
        config()->set('quiz.admin_password', null);

        $this->get(route('admin.leads'))->assertNotFound();
    }

    public function test_admin_acessa_com_senha_correta(): void
    {
        $this->withAdminPassword('segredo');
        Lead::factory()->create(['nome' => 'Fulano de Tal']);

        $this->withBasicAuth('qualquer', 'segredo')
            ->get(route('admin.leads'))
            ->assertOk()
            ->assertSee('Painel')
            ->assertSee('Fulano de Tal');
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

    public function test_export_csv(): void
    {
        $this->withAdminPassword('segredo');
        $lead = Lead::factory()->has(QuizResposta::factory()->comPontuacao(8), 'quizResposta')->create(['nome' => 'Cliente Exportado']);

        $response = $this->withBasicAuth('admin', 'segredo')->get(route('admin.export'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Cliente Exportado', $content);
        $this->assertStringContainsString('amarelo', $content);
        $this->assertStringContainsString('Pontuação', $content);
    }
}
