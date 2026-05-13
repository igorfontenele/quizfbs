<?php

namespace Tests\Unit;

use App\Services\QuizClassifierService;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class QuizClassifierServiceTest extends TestCase
{
    private QuizClassifierService $classifier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classifier = new QuizClassifierService;
    }

    public static function faixaProvider(): array
    {
        return [
            // [pontuacao, classificacao esperada]
            'limite inferior verde' => [0, 'verde'],
            'meio verde' => [3, 'verde'],
            'limite superior verde' => [5, 'verde'],
            'limite inferior amarelo' => [6, 'amarelo'],
            'meio amarelo' => [9, 'amarelo'],
            'limite superior amarelo' => [12, 'amarelo'],
            'limite inferior vermelho' => [13, 'vermelho'],
            'meio vermelho' => [16, 'vermelho'],
            'limite superior vermelho' => [18, 'vermelho'],
        ];
    }

    #[DataProvider('faixaProvider')]
    public function test_classifica_corretamente_cada_faixa(int $total, string $esperado): void
    {
        $resultado = $this->classifier->classify($total);

        $this->assertSame($esperado, $resultado['classificacao']);
        $this->assertSame($total, $resultado['pontuacao_total']);
        $this->assertArrayHasKey('cartilha_slug', $resultado);
        $this->assertArrayHasKey('cor_hex', $resultado);
        $this->assertArrayHasKey('flux_variant', $resultado);
        $this->assertNotEmpty($resultado['titulo']);
        $this->assertNotEmpty($resultado['mensagem']);
        $this->assertNotEmpty($resultado['fechamento']);
    }

    public function test_verde_e_vermelho_apontam_para_cartilhas_corretas(): void
    {
        $this->assertSame('cartilha-de-expansao', $this->classifier->classify(0)['cartilha_slug']);
        $this->assertSame('cartilha-de-gestao-de-riscos', $this->classifier->classify(10)['cartilha_slug']);
        $this->assertSame('cartilha-de-gestao-de-riscos', $this->classifier->classify(18)['cartilha_slug']);
    }

    public function test_pontuacao_negativa_lanca_excecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->classifier->classify(-1);
    }

    public function test_pontuacao_acima_do_maximo_lanca_excecao(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->classifier->classify(19);
    }

    public function test_pontuacao_total_soma_as_respostas(): void
    {
        $this->assertSame(0, $this->classifier->pontuacaoTotal([0, 0, 0, 0, 0, 0, 0, 0, 0]));
        $this->assertSame(18, $this->classifier->pontuacaoTotal([2, 2, 2, 2, 2, 2, 2, 2, 2]));
        $this->assertSame(9, $this->classifier->pontuacaoTotal([1, 1, 1, 1, 1, 1, 1, 1, 1]));
    }

    public function test_todos_os_19_cenarios_de_pontuacao_sao_validos(): void
    {
        for ($i = 0; $i <= 18; $i++) {
            $resultado = $this->classifier->classify($i);
            $this->assertContains($resultado['classificacao'], ['verde', 'amarelo', 'vermelho']);
        }
    }
}
