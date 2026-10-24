# 🧠 Aprendizados do Projeto

> O agente LÊ este arquivo no início de cada conversa e ESCREVE nele ao fim de cada tarefa (skill `memoria`).
> Regras: entradas curtas, factuais, sem duplicatas, sem segredos. Máx. ~150 linhas (consolide as antigas).

## Formato
`- [AAAA-MM-DD] [categoria] Aprendizado. → Ação/regra para o futuro.`
Categorias: `build`, `test`, `arquitetura`, `convenção`, `armadilha`, `preferência-do-usuário`, `ambiente`.

## Entradas
- [2026-10-23] [ambiente] PHP/Composer/Node não existem no host; tudo roda no container `app`. → Subir com `docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d` antes do `verify.sh` (o `docker-compose.yml` sozinho é inválido: `db` está só no `.dev`).
- [2026-10-23] [test] Pest NÃO está instalado (só PHPUnit 11), apesar de citado no `project_instructions.md.bak`. → Escrever testes em PHPUnit.
- [2026-10-23] [build] verify.sh 100% verde (lint, test 43/43, build) desde o commit `fdfa5ce` (Pint no legado). → Qualquer falha nova é regressão; rodar Pint antes de commitar.
- [2026-10-23] [ambiente] Shell do host é zsh: `$VAR` com lista de arquivos não sofre word-splitting. → Passar listas via `xargs`.
- [2026-10-23] [armadilha] `User` usa `HasUuids` (id é string), mas código do starter kit assumia `int` (`ProfileValidationRules`). → Tipar ids de User como `string`, nunca `int`.
- [2026-10-23] [test] Ajustes de layout renomeiam rótulos (ex.: "Saldo Contas" → "Saldo Atual") e quebram `assertSee`. → Ao mudar texto de view, rodar os testes Feature da tela.
- [2026-10-24] [armadilha] Skill ADA `code-review` conflita com a skill nativa homônima do Claude Code; renomeada para `revisao-codigo`. → Ao criar/renomear skills, checar nomes nativos da ferramenta e atualizar todas as referências (AGENTS.md, commands, outras skills).
- [2026-10-24] [convenção] `import-files/` usa `financeiro-mes-AAAA_MM.json` (underscore), com exceção `2027-01`. → Não assumir padrão único ao listar/gerar arquivos de importação.
