# 📋 Especificação da Tarefa: Ajuste do Resumo Financeiro na Visão Mês

> **[META INSTRUÇÃO PARA O AGENTE IA]**
> Este documento define o escopo estrito da sua próxima execução. Você deve ler o arquivo `.agent/project_instructions.md` antes de iniciar. Atualize o status das tarefas no bloco `<execution_plan>` marcando com `[x]` conforme for progredindo.

<specification_meta>
- **Status:** Completed
- **Data de Criação:** 2026-09-27
</specification_meta>

<context>
  - **O que estamos construindo:** Substituição da tabela de Previsto vs Realizado na tela `visaoMes.blade.php` e no método `visaoMes` do `DashboardController` por uma visão de projeção de caixa baseada no saldo atual de contas correntes, receitas pendentes de recebimento no mês, despesas pendentes de pagamento no mês e faturas pendentes de pagamento no mês.
  - **Motivação:** Permitir que o usuário visualize a disponibilidade real de caixa: quanto possui em conta corrente hoje, o que ainda vai entrar e o que ainda precisa pagar no mês selecionado, obtendo o saldo líquido projetado final que sobrará.
</context>

<requirements>
  - [x] 1. No `DashboardController::visaoMes`:
    - Calcular o somatório do saldo atual de todas as contas do tipo `CORRENTE` do usuário logado (`$saldoContasCorrentes`).
    - Calcular o valor total de receitas pendentes (`ja_recebido = false`) para o mês selecionado vinculadas a contas `CORRENTE` (`$receitasParaReceber`).
    - Calcular o valor total de despesas pendentes (`ja_pago = false`) para o mês selecionado vinculadas a contas `CORRENTE` (`$despesasParaPagar`).
    - Calcular o valor total de faturas não pagas (`ja_foi_paga = false`) cujo `mes_referencia` seja o mês selecionado para os cartões do usuário (`$faturasParaPagar`).
    - Calcular o saldo projetado restante: `$saldoRestante = $saldoContasCorrentes + $receitasParaReceber - $despesasParaPagar - $faturasParaPagar`.
    - Manter/estruturar os dados enviados para a view `visaoMes.blade.php` de forma limpa e sem quebrar os outros elementos que dependem de variáveis do controller (como `$graficoBarras`, `$classificacoes`, `$faturas`, `$saldoPrevistoProximoMesSemBeneficio`).
  - [x] 2. Na view `resources/views/visaoMes.blade.php`:
    - Substituir a tabela anterior de "Previsto / Realizado" por uma tabela horizontal estilizada e responsiva com as colunas:
      - **Saldo Contas**: Saldo disponível em contas correntes (cor neutra/verde se positivo, vermelho se negativo).
      - **A Receber**: Total de receitas pendentes no mês (destaque positivo/verde).
      - **Despesas a Pagar**: Total de despesas pendentes no mês (destaque vermelho/alerta).
      - **Faturas a Pagar**: Total de faturas pendentes no mês (destaque vermelho/alerta).
      - **Saldo Restante**: Saldo final projetado após entradas e saídas (destaque dinâmico verde se positivo, vermelho se negativo).
    - Preservar a responsividade em telas mobile (`overflow-x-auto`, tamanhos de fonte consistentes com Tailwind 4 e modo escuro).
  - [x] 3. Criar teste de Feature automatizado cobrindo os novos cálculos e exibição na rota `visaoMes`.
</requirements>

<acceptance_criteria>
  - O cálculo do saldo em contas correntes deve considerar estritamente `tipo = 'CORRENTE'`.
  - As receitas a receber e despesas a pagar do mês selecionado devem considerar apenas movimentações não pagas/não recebidas vinculadas a contas do tipo `CORRENTE`.
  - As faturas a pagar devem somar apenas as faturas não pagas do mês de referência.
  - A fórmula `Saldo Restante = Saldo Contas + A Receber - Despesas a Pagar - Faturas a Pagar` deve estar matematicamente correta.
  - O layout da tabela deve ser responsivo, sem quebra visual em mobile ou desktop, com suporte a dark mode.
  - A suite de testes correspondente deve passar sem falhas via `docker exec laravel_app php artisan test`.
</acceptance_criteria>

<execution_plan>
  <!-- Passos técnicos detalhados para alcançar os requisitos. Marque com [x] ao concluir cada passo. -->
- [x] 1. Ajustar o método `visaoMes` em `app/Http/Controllers/DashboardController.php` calculando os novos valores e organizando a estrutura para a view.
- [x] 2. Atualizar o template `resources/views/visaoMes.blade.php` com a nova tabela estilizada no lugar da antiga tabela Previsto/Realizado.
- [x] 3. Criar teste de Feature em `tests/Feature/VisaoMesTest.php` validando autenticação, cálculos dos valores projetados e renderização na view.
- [x] 4. Executar os testes automatizados e validar conformidade com os `<acceptance_criteria>`.
- [x] 5. Marcar a especificação como Completed.
</execution_plan>
