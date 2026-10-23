# 🤖 Instruções e Contexto do Projeto

> **[META INSTRUÇÃO PARA O AGENTE IA]**
> Este arquivo é a fonte de verdade sobre o projeto. Leia e respeite antes de qualquer plano, código ou spec.
> Valores entre colchetes são placeholders: substitua por dados REAIS. Se não souber, escreva `N/A` (nunca invente).

<project_context>
  - **Nome do Projeto:** Minhas Finanças (Gestor Financeiro Pessoal)
  - **Propósito:** Gestão financeira pessoal: contas, receitas, despesas, cartões de crédito, faturas, compras e classificações de gastos, com dashboard, visão mensal e importação de dados via JSON.
  - **Público Alvo:** Usuários finais (pessoas físicas). Projeto também de caráter educacional/evolutivo.
</project_context>

<tech_stack>
  - **Linguagem Principal:** PHP 8.4 no container (`docker/php/Dockerfile`); `composer.json` exige `^8.2`
  - **Framework Core:** Laravel 12 + Livewire 4 + Livewire Flux 2 (base: laravel/livewire-starter-kit)
  - **Gerenciador de pacotes:** Composer (PHP) e npm (front)
  - **Ferramentas Auxiliares:**
    - Estilização: Tailwind CSS 4 + Vite 7 (laravel-vite-plugin)
    - Banco de Dados / ORM: MySQL 8 (Docker) com Eloquent; testes usam SQLite em memória (`phpunit.xml`)
    - Testes: PHPUnit 11 (`php artisan test`). Pest NÃO está instalado.
    - Autenticação: Laravel Fortify 1.x
    - Qualidade: Laravel Pint (preset `laravel`), barryvdh/laravel-ide-helper
    - Ambiente: Docker Compose (`app` php-fpm com node/npm, `nginx` :8000, `db` mysql :3306, `adminer` :8080). PHP/Node NÃO estão instalados no host.
</tech_stack>

<commands>
  <!-- Formato: "- nome: comando". Lido por .agent/scripts/verify.sh. Use N/A se não existir. -->
  <!-- Requer containers ativos (ver "dev"). -->
  - install: docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -T app sh -c "composer install && npm install"
  - lint: docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -T app ./vendor/bin/pint --test
  - typecheck: N/A
  - test: docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -T app php artisan test
  - build: docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -T app npm run build
  - dev: docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
</commands>

<architecture>
  - **Padrão Principal:** MVC com controllers por recurso + Blade/Flux; Form Requests para validação e Policies para autorização por dono (`user_id`). Livewire usado nas telas do starter kit (auth/settings).
  - **Mapa de Diretórios (Resumo):**
    - `app/Models/`: User, Conta, Receita, Despesa, Classificacao, Cartao, Fatura, Compra, ImportRegister.
    - `app/Http/Controllers/`: um controller por recurso + DashboardController (dashboard, visaoMes) e ImportController.
    - `app/Http/Requests/`: Store*/Update*Request por recurso.
    - `app/Policies/`: Policy por model (propriedade do recurso).
    - `app/Actions/Fortify/`, `app/Concerns/`, `app/Livewire/Actions/`: auth e validação do starter kit.
    - `resources/views/`: views Blade por recurso (+ layouts, components, partials, flux, pages).
    - `import-files/`: JSONs mensais de exemplo para importação (`financeiro-mes-AAAA-MM.json`).
    - `docker/`: Dockerfile PHP e config do nginx.
  - **Pontos de entrada:** `routes/web.php` (rotas, todas com `auth` + `verified`), `public/index.php`, `resources/js/app.js` via Vite.
</architecture>

<coding_guidelines>
  - **Nomenclatura:** domínio em português. Classes PascalCase (`ContaController`, `StoreContaRequest`); métodos/variáveis camelCase; views camelCase (`adicionarContas.blade.php`, `visaoMes.blade.php`); nomes de rota em pontos (`adicionar.conta`, `editar.conta`) ou de resource (`receitas.index`); banco snake_case; rotas aninhadas `cartoes.faturas.compras`.
  - **Tratamento de Erros:** validação via Form Requests; autorização via `Gate::authorize('acao', $model)` + Policies; feedback via `redirect()->route(...)->with('success', '...')`; handler padrão do Laravel.
  - **Tipagem/Comentários:** type hints nos métodos dos controllers (ex.: `update(UpdateContaRequest $request, Conta $conta)`); siga o padrão do arquivo vizinho.
  - **Geração de Código:** código completo, sem placeholders; siga os padrões já existentes.
</coding_guidelines>

<git_conventions>
  - **Branches:** `main` como principal; histórico sem padrão de prefixo. Para novas branches: feat/<slug>, fix/<slug>, chore/<slug>.
  - **Commits:** histórico em pt-BR livre (ex.: "ajustes de layout de tabelas"). Daqui em diante: Conventional Commits em pt-BR.
  - **PRs:** N/A (sem template no repositório).
</git_conventions>

<security>
  - Nunca commitar segredos, tokens ou `.env`.
  - Validar/sanitizar toda entrada externa. Usar consultas parametrizadas.
  - Áreas sensíveis do projeto: autenticação (Fortify), dados financeiros pessoais, isolamento por `user_id` (Policies), importação de JSON (entrada externa).
</security>

<definition_of_done>
  - Lint, typecheck e testes passando (`.agent/scripts/verify.sh`).
  - Todos os `<acceptance_criteria>` da spec atendidos e passos marcados `[x]`.
  - Sem arquivos alterados fora de `<files_in_scope>`.
  - Documentação/README atualizados se o comportamento público mudou.
  - `.agent/memory/learnings.md` atualizado quando houver aprendizado relevante.
</definition_of_done>

<agent_constraints>
  - **PROIBIDO:** alterar `.env`, `.gitignore` ou configs de ambiente sem pedido explícito.
  - **PROIBIDO:** modificar código de dependências instaladas (`node_modules`, `venv`, `vendor`, etc.).
  - **PROIBIDO:** commitar segredos ou executar comandos destrutivos (drop, `rm -rf`, force push) sem confirmação.
  - **OBRIGATÓRIO:** passar por `dependency-eval` antes de adicionar dependências.
  - **OBRIGATÓRIO:** seguir a spec aprovada; escopo fechado em `<files_in_scope>`.
  - **OBRIGATÓRIO:** manter rotas protegidas por middleware `auth` + `verified` (Fortify).
  - **OBRIGATÓRIO:** manter isolamento de dados por usuário (`user_id` nas models principais + Policy no controller).
  - **OBRIGATÓRIO:** rodar comandos PHP/Node dentro do container `app` (não há PHP/Node no host).
</agent_constraints>

<available_skills>
  - Skills em `.agent/skills/`. Consulte o índice no `AGENTS.md`.
</available_skills>
