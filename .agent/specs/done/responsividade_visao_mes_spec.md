# 📋 Especificação da Tarefa: Melhorias de Responsividade na Visão por Mês

<specification_meta>
- **Status:** Completed
- **Data de Criação:** 2026-09-26
</specification_meta>

<context>
  - **O que estamos construindo:** Otimização responsiva completa da view resources/views/visaoMes.blade.php, corrigindo quebras de layout e textos espremidos em dispositivos móveis e telas intermediárias (tablets/laptops compactos).
  - **Motivação:** O card "Tabela Previsto/Realizado" e os cards adjacentes quebram em telas menores devido ao grid de 3 colunas em telas `md`, largura fixa excessiva e falta de tratamento de `whitespace-nowrap` para valores monetários ("R$"). Além disso, o cabeçalho e o gráfico de pizza ficam comprimidos em telas menores que 640px.
</context>

<requirements>
  - [x] 1. Ajustar o topo (título e filtro de mês) para layout flexível e fluido (`flex-col sm:flex-row gap-3 sm:items-center sm:justify-between`).
  - [x] 2. Reconfigurar o grid da primeira linha de cards para `grid-cols-1 lg:grid-cols-3` (em vez de `md:grid-cols-3`), garantindo espaço confortável em telas compactas e tablets.
  - [x] 3. Otimizar o card e a tabela "Previsto/Realizado":
    - Adicionar padding responsivo no card (`p-4 sm:p-6`).
    - Aplicar tipografia escalável na tabela (`text-xs sm:text-sm`).
    - Adicionar `whitespace-nowrap` em células e valores monetários para evitar quebra entre "R$" e o número.
    - Ajustar paddings internos das células (`py-3 px-2 sm:px-3 sm:py-4`) e ajustar a rolagem horizontal (`overflow-x-auto`) de forma suave sem estourar o container.
  - [x] 4. Ajustar a tipografia do valor de saldo no card "Saldo Previsto Próximo Mês" (`text-3xl sm:text-4xl lg:text-3xl xl:text-4xl whitespace-nowrap`) e padding `p-4 sm:p-6`.
  - [x] 5. Ajustar o card "Faturas Cartões Próximos Mês" com padding e alinhamento responsivo.
  - [x] 6. Otimizar o card "Gastos por área" (Pizza/Legenda) para empilhamento no mobile (`flex-col sm:flex-row`) evitando corte de legendas em telas pequenas (<640px).
</requirements>

<acceptance_criteria>
  - Nenhuma quebra visual de linha nos valores monetários ("R$").
  - O card da tabela não deve estourar a largura da tela em resoluções mobile (360px a 430px) e deve permitir visualização fluida.
  - O topo não deve sobrepor o título com o formulário de filtro em telas mobile.
  - O gráfico de gastos por área deve exibir legendas legíveis sem cortar nomes de categorias no mobile.
  - Sintaxe Blade válida e sem quebra dos scripts Chart.js existentes.
</acceptance_criteria>

<execution_plan>
- [x] 1. Criar o arquivo de especificação `.agent/specs/responsividade_visao_mes_spec.md`.
- [x] 2. Aplicar as alterações responsivas no cabeçalho e no grid da Linha 1 de resources/views/visaoMes.blade.php.
- [x] 3. Refinar a tabela Previsto/Realizado com `whitespace-nowrap`, tipografia compacta e paddings adaptáveis.
- [x] 4. Ajustar os cards de Saldo Previsto e Faturas de Cartões.
- [x] 5. Ajustar o layout do card de Gastos por área (gráfico de pizza + legenda) para mobile/desktop.
- [x] 6. Validar a sintaxe do arquivo Blade e conformidade com os critérios de aceite.
</execution_plan>
