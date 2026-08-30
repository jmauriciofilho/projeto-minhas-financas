# 🤖 Instruções e Contexto do Projeto

> **[META INSTRUÇÃO PARA O AGENTE IA]**
> Este arquivo contém a verdade absoluta sobre o projeto, incluindo stack, padrões arquiteturais e regras estritas. Você DEVE ler e respeitar estas restrições e diretrizes antes de iniciar qualquer plano de execução, alteração de código ou especificação (spec).

<project_context>
  - **Nome do Projeto:** Minhas Finanças
  - **Propósito:** Aplicação de gestão financeira pessoal para controle de contas, receitas, despesas, cartões de crédito, faturas e classificações de gastos.
  - **Público Alvo:** Usuários finais (pessoas físicas) para gerenciamento de finanças pessoais.
</project_context>

<tech_stack>
  - **Linguagem Principal:** PHP 8.2+
  - **Framework Core:** Laravel 12.x com Livewire 4.x (Livewire Flux 2.x para UI)
  - **Ferramentas Auxiliares:** 
    - Estilização: Tailwind CSS 4.x + Vite 7.x
    - Banco de Dados / ORM: MySQL com Eloquent ORM (Laravel)
    - Testes: PHPUnit 11.x + Pest (configurado via phpunit.xml)
    - Autenticação: Laravel Fortify 1.x
    - Code Quality: Laravel Pint (PSR-12), Laravel IDE Helper
</tech_stack>

<architecture>
  - **Padrão Principal:** MVC (Model-View-Controller) com componentes Livewire para interatividade frontend. Uso de Policies para autorização, Form Requests para validação e Actions para lógica de negócio isolada.
  - **Mapa de Diretórios (Resumo):**
    - **app/Models/**: Entidades Eloquent (User, Conta, Cartao, Fatura, Compra, Despesa, Receita, Classificacao, ImportRegister) - mapeamento direto das tabelas do banco.
    - **app/Http/Controllers/**: Controllers RESTful por recurso (ContaController, CartaoController, DespesaController, ReceitaController, FaturaController, CompraController, ClassificacaoController, ImportController, DashboardController).
    - **app/Http/Requests/**: Form Requests para validação de entrada (Store/Update para cada resource).
    - **app/Policies/**: Policies de autorização por model (ex: ContaPolicy, CartaoPolicy, DespesaPolicy, ReceitaPolicy, FaturaPolicy, CompraPolicy, ClassificacaoPolicy) - validam a propriedade dos recursos com base no usuário autenticado.
    - **app/Livewire/Actions/**: Ações Livewire (ex: Logout.php).
    - **resources/views/**: Templates Blade organizados por recurso (contas.blade.php, cartoes.blade.php, dashboard.blade.php, etc.) + layouts, components, partials e flux/.
    - **routes/web.php**: Rotas web com resource controllers e rotas customizadas para ações específicas (updateStatus, updateClassificacao, importacao).
    - **tests/**: Testes Feature e Unit com TestCase base.
</architecture>

<coding_guidelines>
  - **Nomenclatura:** 
    - Classes/Models/Controllers/Policies: PascalCase (ex: `ContaController`, `StoreContaRequest`)
    - Variáveis/Propriedades/Métodos: camelCase (ex: `$conta->nome`, `$request->saldo`)
    - Views Blade: camelCase predominantemente (ex: `adicionarContas.blade.php`, `editarContas.blade.php`), algumas em lowercase (ex: `cartao.blade.php`)
    - Rotas: kebab-case nos nomes (ex: `adicionar.conta`, `atualizar.conta`) e URLs
    - Banco de dados: snake_case (padrão Laravel/Eloquent)
  - **Tratamento de Erros:** 
    - Validação: Form Requests (`StoreContaRequest`, `UpdateContaRequest`) com mensagens automáticas
    - Autorização: Uso de Policies e Gates (`Gate::authorize('action', $model)`) para controle de acesso granular
    - Exceções: Laravel exception handler padrão; uso de `abort(403)` para acesso não autorizado
    - Flash messages: `redirect()->with('success', 'Mensagem')` para feedback de sucesso
  - **Geração de Código:** 
    - Escreva código completo, não use placeholders como `// implemente o resto aqui`.
    - Siga os padrões de tipagem (return types, property types) já existentes no repositório.
    - Use type hints nos métodos dos controllers (ex: `public function update(UpdateContaRequest $request, Conta $conta)`)
    - Mantenha consistência com a estrutura de pastas atual (Controllers, Requests, Policies, Models separados).
</coding_guidelines>

<agent_constraints>
  - **PROIBIDO:** Remover ou alterar configurações de ambiente (`.env`, `.gitignore`) a menos que explicitamente solicitado.
  - **PROIBIDO:** Modificar código de bibliotecas externas (vendor/, node_modules/).
  - **OBRIGATÓRIO:** Antes de sugerir novas dependências, verifique se não é possível usar a stack atual (Laravel, Livewire, Tailwind, Eloquent).
  - **OBRIGATÓRIO:** Siga estritamente o documento `spec.md` (quando fornecido).
  - **OBRIGATÓRIO:** Respeitar a autenticação via Laravel Fortify (rotas protegidas por middleware `auth`, `verified`).
  - **OBRIGATÓRIO:** Manter isolamento de dados por usuário (`user_id` em todas as models principais).
</agent_constraints>

<available_skills>
  - O agente possui habilidades modulares na pasta `/.agent/skills/`.
  - Consulte o diretório de skills se precisar de contexto adicional sobre tarefas.
</available_skills>