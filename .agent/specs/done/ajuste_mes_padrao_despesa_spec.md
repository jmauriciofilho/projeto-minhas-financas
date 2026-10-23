# 📋 Especificação da Tarefa: Ajuste do Mês Padrão na Criação de Despesa

> **[META INSTRUÇÃO PARA O AGENTE IA]**
> Este documento define o escopo estrito da sua próxima execução. Você deve ler o arquivo `.agent/project_instructions.md` antes de iniciar. Atualize o status das tarefas no bloco `<execution_plan>` marcando com `[x]` conforme for progredindo.

<specification_meta>
- **Status:** Completed
- **Data de Criação:** 2026-09-26
</specification_meta>

<context>
  - **O que estamos construindo:** Correção do valor inicial pré-selecionado no campo "Mês da Despesa" do formulário de criação de despesas para que carregue o mês e ano correntes.
  - **Motivação:** Evitar que a primeira opção da lista (-6 meses, anteriormente 03/2026) seja selecionada por padrão devido à divergência de formato entre `now()->month` (int) e `Y-m` (string).
</context>

<requirements>
  - [x] Requisito 1: Alterar a variável `$mesAtual` em `resources/views/adicionarDespesa.blade.php` para utilizar o formato `now()->format('Y-m')` quando `old('mes')` não estiver preenchido.
  - [x] Requisito 2: Garantir que o `<option>` correspondente ao mês e ano atual receba o atributo `selected`.
</requirements>

<acceptance_criteria>
- Ao carregar a tela de adicionar despesa sem submissão prévia, a opção selecionada no campo `mes` deve corresponder ao mês e ano atual (`now()->format('Y-m')` / `now()->format('m/Y')`).
- Se houver valor prévio na sessão via `old('mes')`, este deve ter precedência sobre o valor padrão.
- Nenhuma dependência externa deve ser instalada ou alterada.
</acceptance_criteria>

<execution_plan>
- [x] 1. Atualizar o fallback de `$mesAtual` em `resources/views/adicionarDespesa.blade.php` para `old('mes', now()->format('Y-m'))`.
- [x] 2. Validar a renderização da view e a conformidade com os `<acceptance_criteria>`.
</execution_plan>
