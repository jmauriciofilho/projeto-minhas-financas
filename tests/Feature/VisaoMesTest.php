<?php

namespace Tests\Feature;

use App\Models\Cartao;
use App\Models\Conta;
use App\Models\Despesa;
use App\Models\Fatura;
use App\Models\Receita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisaoMesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('visaoMes'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_monthly_projection_with_correct_calculations(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Contas
        $contaCorrente1 = Conta::create([
            'nome' => 'Banco Principal',
            'tipo' => 'CORRENTE',
            'saldo' => 1000.00,
            'user_id' => $user->id,
        ]);

        $contaCorrente2 = Conta::create([
            'nome' => 'Banco Secundário',
            'tipo' => 'CORRENTE',
            'saldo' => 500.00,
            'user_id' => $user->id,
        ]);

        $contaBeneficio = Conta::create([
            'nome' => 'Vale Refeição',
            'tipo' => 'BENEFICIO',
            'saldo' => 350.00,
            'user_id' => $user->id,
        ]);

        $mes = '2026-09';

        // Receitas
        Receita::create([
            'nome' => 'Salário',
            'valor' => 2000.00,
            'mes' => $mes,
            'ja_recebido' => false,
            'data_recebimento' => '2026-09-05',
            'conta_id' => $contaCorrente1->id,
            'user_id' => $user->id,
        ]);

        Receita::create([
            'nome' => 'Freelance Já Recebido',
            'valor' => 800.00,
            'mes' => $mes,
            'ja_recebido' => true,
            'data_recebimento' => '2026-09-01',
            'conta_id' => $contaCorrente1->id,
            'user_id' => $user->id,
        ]);

        Receita::create([
            'nome' => 'Recarga Benefício',
            'valor' => 600.00,
            'mes' => $mes,
            'ja_recebido' => false,
            'data_recebimento' => '2026-09-01',
            'conta_id' => $contaBeneficio->id,
            'user_id' => $user->id,
        ]);

        // Despesas
        Despesa::create([
            'nome' => 'Aluguel',
            'valor' => 500.00,
            'mes' => $mes,
            'recorrente' => false,
            'ja_pago' => false,
            'data_pagamento' => '2026-09-10',
            'conta_id' => $contaCorrente2->id,
            'user_id' => $user->id,
        ]);

        Despesa::create([
            'nome' => 'Internet Paga',
            'valor' => 120.00,
            'mes' => $mes,
            'recorrente' => false,
            'ja_pago' => true,
            'data_pagamento' => '2026-09-02',
            'conta_id' => $contaCorrente2->id,
            'user_id' => $user->id,
        ]);

        Despesa::create([
            'nome' => 'Almoço Benefício',
            'valor' => 80.00,
            'mes' => $mes,
            'recorrente' => false,
            'ja_pago' => false,
            'data_pagamento' => '2026-09-03',
            'conta_id' => $contaBeneficio->id,
            'user_id' => $user->id,
        ]);

        // Cartão e Faturas
        $cartao = Cartao::create([
            'nome' => 'Cartão Black',
            'final_cartao' => '4321',
            'user_id' => $user->id,
        ]);

        Fatura::create([
            'mes_referencia' => $mes,
            'data_fechamento' => '2026-09-20',
            'data_vencimento' => '2026-09-28',
            'despesa_total' => 400.00,
            'ja_foi_paga' => false,
            'cartao_id' => $cartao->id,
            'conta_id' => $contaCorrente1->id,
        ]);

        Fatura::create([
            'mes_referencia' => $mes,
            'data_fechamento' => '2026-09-10',
            'data_vencimento' => '2026-09-15',
            'despesa_total' => 150.00,
            'ja_foi_paga' => true,
            'cartao_id' => $cartao->id,
            'conta_id' => $contaCorrente1->id,
        ]);

        Fatura::create([
            'mes_referencia' => '2026-10',
            'data_fechamento' => '2026-10-20',
            'data_vencimento' => '2026-10-28',
            'despesa_total' => 300.00,
            'ja_foi_paga' => false,
            'cartao_id' => $cartao->id,
            'conta_id' => $contaCorrente1->id,
        ]);

        // Saldo esperado:
        // Contas Correntes: 1000 + 500 = 1500.00
        // A Receber (pendente em corrente): 2000.00
        // Despesas a Pagar (pendente em corrente): 500.00
        // Faturas a Pagar (pendente do mês): 400.00
        // Saldo Restante: 1500 + 2000 - 500 - 400 = 2600.00

        // Act
        $this->actingAs($user);
        $response = $this->get(route('visaoMes', ['mes' => $mes]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('visaoMes');

        $response->assertViewHas('projecao', [
            'saldo_contas' => 1500.00,
            'receitas_a_receber' => 2000.00,
            'despesas_a_pagar' => 500.00,
            'faturas_a_pagar' => 400.00,
            'saldo_restante' => 2600.00,
        ]);

        $response->assertSee('Projeção do Mês');
        $response->assertSee('Saldo Contas');
        $response->assertSee('A Receber');
        $response->assertSee('Despesas a Pagar');
        $response->assertSee('Faturas a Pagar');
        $response->assertSee('Saldo Restante');
        $response->assertSee('1.500,00');
        $response->assertSee('2.000,00');
        $response->assertSee('500,00');
        $response->assertSee('400,00');
        $response->assertSee('2.600,00');
    }

    public function test_user_data_isolation_in_monthly_projection(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $mes = '2026-09';

        // User A data
        $contaA = Conta::create([
            'nome' => 'Conta User A',
            'tipo' => 'CORRENTE',
            'saldo' => 5000.00,
            'user_id' => $userA->id,
        ]);

        Receita::create([
            'nome' => 'Receita A',
            'valor' => 1000.00,
            'mes' => $mes,
            'ja_recebido' => false,
            'conta_id' => $contaA->id,
            'user_id' => $userA->id,
        ]);

        // User B data (should be 0 for user B)
        $this->actingAs($userB);
        $response = $this->get(route('visaoMes', ['mes' => $mes]));

        $response->assertOk();
        $response->assertViewHas('projecao', [
            'saldo_contas' => 0.0,
            'receitas_a_receber' => 0.0,
            'despesas_a_pagar' => 0.0,
            'faturas_a_pagar' => 0.0,
            'saldo_restante' => 0.0,
        ]);
    }
}
