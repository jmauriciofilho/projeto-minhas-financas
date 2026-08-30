# 📋 Especificação da Tarefa: Padronização de Autorização com Gates/Policies

<specification_meta>
- **Status:** Completed
- **Data de Criação:** 2026-08-30
</specification_meta>

<context>
  - **O que estamos construindo:** Refatoração da camada de autorização em todos os Controllers do sistema, migrando as verificações manuais (`if ($model->user_id !== Auth::id()) abort(403);`) para o padrão `Gate::authorize(...)` integrado às Policies do Laravel (como implementado no `CartaoController`).
  - **Motivação:** Centralizar as regras de acesso e permissão nas Policies (`app/Policies/`), eliminando duplicação de lógica e mantendo consistência e clareza no fluxo de autorização dos Controllers.
</context>

<requirements>
  - [x] Requisito 1: Atualizar as Policies (`ContaPolicy`, `DespesaPolicy`, `ReceitaPolicy`, `FaturaPolicy`, `CompraPolicy`) para validar corretamente a propriedade do recurso em relação ao usuário autenticado (`$model->user_id === $user->id` ou relação direta).
  - [x] Requisito 2: Refatorar `ContaController` utilizando `Gate::authorize()` nos métodos `edit`, `update` e `destroy`.
  - [x] Requisito 3: Refatorar `DespesaController` utilizando `Gate::authorize()` nos métodos `edit`, `update`, `updateStatus`, `destroy` e `updateClassificacao`.
  - [x] Requisito 4: Refatorar `ReceitaController` utilizando `Gate::authorize()` nos métodos `edit`, `update`, `updateStatus` e `destroy`.
  - [x] Requisito 5: Refatorar `FaturaController` e `CompraController` utilizando `Gate::authorize()` para validação de acesso a cartões, faturas e compras.
</requirements>

<acceptance_criteria>
  - Todas as chamadas de autorização devem utilizar `Gate::authorize(...)`.
  - Nenhuma verificação manual redundante com `abort(403)` deve permanecer nos métodos refatorados.
  - As Policies correspondentes devem conter a lógica de autorização devidamente implementada (não retornando apenas `false`).
  - Código limpo, aderente à PSR-12 e sem erros de sintaxe.
</acceptance_criteria>

<execution_plan>
- [x] 1. Atualizar as Policies em `app/Policies/` (`ContaPolicy.php`, `DespesaPolicy.php`, `ReceitaPolicy.php`, `CompraPolicy.php`, `FaturaPolicy.php`).
- [x] 2. Refatorar `app/Http/Controllers/ContaController.php`.
- [x] 3. Refatorar `app/Http/Controllers/DespesaController.php`.
- [x] 4. Refatorar `app/Http/Controllers/ReceitaController.php`.
- [x] 5. Refatorar `app/Http/Controllers/FaturaController.php`.
- [x] 6. Refatorar `app/Http/Controllers/CompraController.php`.
- [x] 7. Validar código contra os critérios de aceite e atualizar o documento `project_instructions.md`.
</execution_plan>
