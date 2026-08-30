<?php

namespace Tests\Unit;

use App\Models\Cartao;
use App\Models\Classificacao;
use App\Models\Compra;
use App\Models\Conta;
use App\Models\Despesa;
use App\Models\Fatura;
use App\Models\Receita;
use App\Models\User;
use App\Policies\ClassificacaoPolicy;
use App\Policies\CompraPolicy;
use App\Policies\ContaPolicy;
use App\Policies\DespesaPolicy;
use App\Policies\FaturaPolicy;
use App\Policies\ReceitaPolicy;
use PHPUnit\Framework\TestCase;

class PolicyAuthorizationTest extends TestCase
{
    public function test_conta_policy(): void
    {
        $user = new User;
        $user->id = 'user-1';

        $otherUser = new User;
        $otherUser->id = 'user-2';

        $conta = new Conta;
        $conta->user_id = 'user-1';

        $policy = new ContaPolicy;

        $this->assertTrue($policy->view($user, $conta));
        $this->assertTrue($policy->update($user, $conta));
        $this->assertTrue($policy->delete($user, $conta));

        $this->assertFalse($policy->view($otherUser, $conta));
        $this->assertFalse($policy->update($otherUser, $conta));
        $this->assertFalse($policy->delete($otherUser, $conta));
    }

    public function test_despesa_policy(): void
    {
        $user = new User;
        $user->id = 'user-1';

        $otherUser = new User;
        $otherUser->id = 'user-2';

        $despesa = new Despesa;
        $despesa->user_id = 'user-1';

        $policy = new DespesaPolicy;

        $this->assertTrue($policy->view($user, $despesa));
        $this->assertTrue($policy->update($user, $despesa));
        $this->assertTrue($policy->delete($user, $despesa));

        $this->assertFalse($policy->view($otherUser, $despesa));
        $this->assertFalse($policy->update($otherUser, $despesa));
        $this->assertFalse($policy->delete($otherUser, $despesa));
    }

    public function test_receita_policy(): void
    {
        $user = new User;
        $user->id = 'user-1';

        $otherUser = new User;
        $otherUser->id = 'user-2';

        $receita = new Receita;
        $receita->user_id = 'user-1';

        $policy = new ReceitaPolicy;

        $this->assertTrue($policy->view($user, $receita));
        $this->assertTrue($policy->update($user, $receita));
        $this->assertTrue($policy->delete($user, $receita));

        $this->assertFalse($policy->view($otherUser, $receita));
        $this->assertFalse($policy->update($otherUser, $receita));
        $this->assertFalse($policy->delete($otherUser, $receita));
    }

    public function test_fatura_policy(): void
    {
        $user = new User;
        $user->id = 'user-1';

        $otherUser = new User;
        $otherUser->id = 'user-2';

        $cartao = new Cartao;
        $cartao->user_id = 'user-1';

        $fatura = new Fatura;
        $fatura->setRelation('cartao', $cartao);

        $policy = new FaturaPolicy;

        $this->assertTrue($policy->view($user, $fatura));
        $this->assertTrue($policy->update($user, $fatura));
        $this->assertTrue($policy->delete($user, $fatura));

        $this->assertFalse($policy->view($otherUser, $fatura));
        $this->assertFalse($policy->update($otherUser, $fatura));
        $this->assertFalse($policy->delete($otherUser, $fatura));
    }

    public function test_compra_policy(): void
    {
        $user = new User;
        $user->id = 'user-1';

        $otherUser = new User;
        $otherUser->id = 'user-2';

        $cartao = new Cartao;
        $cartao->user_id = 'user-1';

        $fatura = new Fatura;
        $fatura->setRelation('cartao', $cartao);

        $compra = new Compra;
        $compra->setRelation('fatura', $fatura);

        $policy = new CompraPolicy;

        $this->assertTrue($policy->view($user, $compra));
        $this->assertTrue($policy->update($user, $compra));
        $this->assertTrue($policy->delete($user, $compra));

        $this->assertFalse($policy->view($otherUser, $compra));
        $this->assertFalse($policy->update($otherUser, $compra));
        $this->assertFalse($policy->delete($otherUser, $compra));
    }

    public function test_classificacao_policy(): void
    {
        $user = new User;
        $user->id = 'user-1';

        $otherUser = new User;
        $otherUser->id = 'user-2';

        $classificacao = new Classificacao;
        $classificacao->user_id = 'user-1';

        $policy = new ClassificacaoPolicy;

        $this->assertTrue($policy->view($user, $classificacao));
        $this->assertTrue($policy->update($user, $classificacao));
        $this->assertTrue($policy->delete($user, $classificacao));

        $this->assertFalse($policy->view($otherUser, $classificacao));
        $this->assertFalse($policy->update($otherUser, $classificacao));
        $this->assertFalse($policy->delete($otherUser, $classificacao));
    }
}
