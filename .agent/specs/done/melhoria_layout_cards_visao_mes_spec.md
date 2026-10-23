# 📋 Especificação da Tarefa: Melhoria de Disposição dos Cards na Visão Mês

> **[META INSTRUÇÃO PARA O AGENTE IA]**
> Este documento define o escopo estrito da sua próxima execução. Você deve ler o arquivo `.agent/project_instructions.md` antes de iniciar. Atualize o status das tarefas no bloco `<execution_plan>` marcando com `[x]` conforme for progredindo.

<specification_meta>
- **Status:** Completed
- **Data de Criação:** 2026-09-27
</specification_meta>

<context>
  - **O que estamos construindo:** Reestruturação da disposição dos blocos visuais na tela `resources/views/visaoMes.blade.php`. A tabela "Projeção do Mês" passará a ocupar a largura total no topo como banner de destaque da página; logo abaixo, os cards de "Saldo Previsto Próximo Mês" e "Faturas Cartões Próximo Mês" serão exibidos de forma compacta e elegante em 2 colunas; e na sequência, os gráficos de receitas/despesas e gastos por área em grid de 2 colunas.
  - **Motivação:** Melhorar a ergonomia visual, eliminar espaços em branco desnecessários e conferir uma estética de dashboard profissional e elegante, permitindo que a tabela de 5 colunas respire em largura total e os cards secundários fiquem compactos e proporcionais.
</context>

<requirements>
  - [x] 1. Na view `resources/views/visaoMes.blade.php`:
    - Estruturar o card "Projeção do Mês" em largura total (`w-full`) no topo do dashboard, proporcionando espaço ideal para as 5 colunas da projeção financeira.
    - Criar uma linha intermediária com grid `grid-cols-1 md:grid-cols-2 gap-4` contendo:
      - Card **Saldo Previsto Próximo Mês**: redesign compacto no padrão KPI, com tipografia equilibrada e alinhamento central harmônico.
      - Card **Faturas Cartões Próximo Mês**: redesign compacto, mantendo a listagem com rolagem interna suave e botão "Ver Cartões".
    - Manter a linha inferior de gráficos (`grid-cols-1 md:grid-cols-2 gap-4`) contendo o gráfico de barras e o gráfico de rosca de gastos por área.
    - Garantir compatibilidade e responsividade para mobile, tablet e desktop, sem overflow horizontal da página.
    - Preservar o suporte ao tema claro e escuro (Dark Mode).
  - [x] 2. Validar que todos os testes automatizados existentes (incluindo `VisaoMesTest`) continuem passando sem regressões.
</requirements>

<acceptance_criteria>
  - A tabela de Projeção do Mês deve ocupar a largura total do container no topo.
  - Os cards de Saldo Previsto e Faturas Cartões devem ficar lado a lado a partir do breakpoint `md` (768px+) de forma compacta e alinhada.
  - Em telas mobile (< 768px), os cards devem empilhar verticalmente de forma fluida.
  - Os gráficos de barra e rosca devem continuar renderizando corretamente sem distorção.
  - A suite de testes `VisaoMesTest` deve ser executada com 100% de sucesso.
</acceptance_criteria>

<execution_plan>
  <!-- Passos técnicos detalhados para alcançar os requisitos. Marque com [x] ao concluir cada passo. -->
- [x] 1. Atualizar o layout Blade em `resources/views/visaoMes.blade.php` com a nova estrutura de 3 blocos (Projeção Full-Width -> Cards Compactos 2-Cols -> Gráficos 2-Cols).
- [x] 2. Ajustar os estilos e espaçamentos (paddings, alturas e fontes) para manter a harmonia visual entre os cards compactos.
- [x] 3. Executar os testes automatizados para garantir que nenhuma asserção foi quebrada.
- [x] 4. Marcar a especificação como Completed.
</execution_plan>
