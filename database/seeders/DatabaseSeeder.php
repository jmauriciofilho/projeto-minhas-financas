<?php

namespace Database\Seeders;

use App\Models\Cartao;
use App\Models\Classificacao;
use App\Models\Compra;
use App\Models\Conta;
use App\Models\Despesa;
use App\Models\Fatura;
use App\Models\Receita;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);

        // $contaCorrente = Conta::factory()->create([
        //     'nome' => 'Conta Corrente',
        //     'tipo' => 'CORRENTE',
        //     'saldo' => 0.00,
        //     'user_id' => $user->id,
        // ]);

        // $contaBeneficio = Conta::factory()->create([
        //     'nome' => 'Benefício',
        //     'tipo' => 'BENEFICIO',
        //     'saldo' => 0.00,
        //     'user_id' => $user->id,
        // ]);

        // Receita::factory()->create([
        //     'nome' => 'Salário',
        //     'valor' => 5000.00,
        //     'ja_recebido' => false,
        //     'mes' => '2026-04',
        //     'user_id' => $user->id,
        //     'conta_id' => $contaCorrente->id,
        // ]);

        // Receita::factory()->create([
        //     'nome' => 'Benefício',
        //     'valor' => 1500.00,
        //     'ja_recebido' => false,
        //     'mes' => '2026-04',
        //     'user_id' => $user->id,
        //     'conta_id' => $contaCorrente->id,
        // ]);

        // $classificacaoSupermercado = Classificacao::factory()->create([
        //     'nome' => 'Supermercado',
        //     'slug' => 'supermercado',
        //     'user_id' => $user->id,
        //     'background_color' => '#FF5733',
        // ]);

        // $classificacaoMoradia = Classificacao::factory()->create([
        //     'nome' => 'Moradia',
        //     'slug' => 'moradia',
        //     'user_id' => $user->id,
        //     'background_color' => '#33C1FF',
        // ]);

        // Despesa::factory()->create([
        //     'nome' => 'Luz',
        //     'valor' => 200.00,
        //     'ja_pago' => false,
        //     'mes' => '2026-04',
        //     'user_id' => $user->id,
        //     'conta_id' => $contaCorrente->id,
        //     'classificacao_id' => $classificacaoMoradia->id,
        // ]);

        // Despesa::factory()->create([
        //     'nome' => 'Prestação do Apartamento',
        //     'valor' => 800.00,
        //     'ja_pago' => false,
        //     'mes' => '2026-04',
        //     'user_id' => $user->id,
        //     'conta_id' => $contaCorrente->id,
        //     'classificacao_id' => $classificacaoMoradia->id,
        // ]);

        // $cartao = Cartao::factory()->create([
        //     'nome' => 'Cartão Nubank',
        //     'final_cartao' => '1234',
        //     'user_id' => $user->id,
        // ]);

        // $fatura = Fatura::factory()->create([
        //     'mes_referencia' => '2026-04',
        //     'data_fechamento' => '2026-03-25',
        //     'data_vencimento' => '2026-04-05',
        //     'despesa_total' => 0.00,
        //     'ja_foi_paga' => false,
        //     'cartao_id' => $cartao->id,
        //     'conta_id' => $contaCorrente->id,
        // ]);

        // Compra::factory()->create([
        //     'descricao' => 'Compra no supermercado',
        //     'valor' => 150.00,
        //     'data_compra' => '2026-03-28',
        //     'total_parcelas' => 1,
        //     'numero_parcela' => 1,
        //     'fatura_id' => $fatura->id,
        //     'classificacao_id' => $classificacaoSupermercado->id,
        // ]);

        // $fatura->despesa_total += 150.00;
        // $fatura->save();
    }
}
