# 📋 Especificação da Tarefa: Ajuste de Layout dos Cards de Saldo e Faturas na Visão Mês

<specification_meta>
- **Status:** Completed
- **Data de Criação:** 2026-09-26
</specification_meta>

<context>
  - **O que estamos construindo:** Reestruturação do grid da primeira linha da tela resources/views/visaoMes.blade.php para que os cards de "Saldo Previsto" e "Faturas Cartões" fiquem lado a lado na mesma linha em telas médias, empilhando somente em telas bem menores (mobile).
  - **Motivação:** Em tablets e telas intermediárias, a visualização dos dois cards lado a lado aproveita melhor o espaço vertical e horizontal da tela, deixando a Tabela em largura total no topo para máxima legibilidade.
</context>

<requirements>
  - [x] 1. Configurar o grid da Linha 1 como `grid-cols-1 sm:grid-cols-2 xl:grid-cols-3`.
  - [x] 2. Configurar o card "Tabela Previsto/Realizado" com `sm:col-span-2 xl:col-span-1` para ocupar a linha superior inteira em telas médias (`sm` até `lg`) e 1 coluna em telas grandes (`xl`).
  - [x] 3. Configurar os cards "Saldo Previsto" e "Faturas Cartões" para dividirem a mesma linha em telas a partir de `sm` (640px+), e empilharem verticalmente apenas em telas pequenas (<640px).
</requirements>

<acceptance_criteria>
  - Em telas grandes (`xl` / 1280px+): os 3 cards devem ficar distribuídos em 3 colunas na mesma linha.
  - Em telas médias (`sm` a `lg` / 640px a 1279px): a tabela fica na linha superior em largura total e os cards de Saldo Previsto e Faturas ficam na mesma linha dividindo 50% cada.
  - Em telas pequenas (< 640px): os 3 cards empilham verticalmente (1 coluna).
  - Sintaxe Blade válida e sem regressões visuais.
</acceptance_criteria>

<execution_plan>
- [x] 1. Criar o arquivo de especificação `.agent/specs/layout_saldo_faturas_visao_mes_spec.md`.
- [x] 2. Ajustar as classes do grid e dos cards da Linha 1 em resources/views/visaoMes.blade.php.
- [x] 3. Validar a compilação do Blade e conformidade com os critérios de aceite.
- [x] 4. Atualizar a especificação para Completed.
</execution_plan>
