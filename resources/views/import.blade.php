<x-layouts::app :title="__('Importação JSON')">

<form id="formImportacao" method="POST" action="{{ route('importacao.json') }}">
    @csrf

    <div class="flex h-full w-full flex-1 flex-col gap-6">

        {{-- TOPO: TÍTULO + AÇÕES RESPONSIVAS --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Importação de Dados (JSON)
                </h1>
                <p class="text-xs text-neutral-500 mt-0.5">Carregue ou cole sua estrutura JSON para importação em lote</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- SELECT TIPO IMPORTAÇÃO --}}
                <select 
                    name="tipo_importacao"
                    id="tipoImportacao"
                    class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-xs sm:text-sm text-neutral-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400"
                >
                    <option value="">Selecione o tipo de dados...</option>
                    <option value="classificacao">Classificações</option>
                    <option value="financeiro_mes">Financeiro Completo do Mês</option>
                </select>

                {{-- BOTÃO CARREGAR ARQUIVO --}}
                <button 
                    type="button" 
                    onclick="document.getElementById('fileInput').click()" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-xs sm:text-sm font-medium rounded-xl transition shadow-sm"
                >
                    <svg class="w-4 h-4 text-neutral-500 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Carregar Arquivo</span>
                </button>

                {{-- BOTÃO FORMATAR --}}
                <button 
                    type="button" 
                    onclick="formatJson()" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 text-xs sm:text-sm font-medium rounded-xl transition shadow-sm"
                    title="Formatar indentação do JSON"
                >
                    <svg class="w-4 h-4 text-neutral-500 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16" />
                    </svg>
                    <span>Formatar</span>
                </button>

                {{-- BOTÃO VALIDAR --}}
                <button 
                    type="button" 
                    onclick="validateJson()" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900/60 hover:bg-blue-100 text-xs sm:text-sm font-medium rounded-xl transition shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Validar</span>
                </button>

                {{-- BOTÃO LIMPAR --}}
                <button 
                    type="button" 
                    onclick="clearJson()" 
                    class="p-2 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition"
                    title="Limpar editor"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                    </svg>
                </button>

                {{-- BOTÃO ENVIAR --}}
                <button 
                    type="button" 
                    onclick="handleSubmit()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-medium rounded-xl transition shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Importar Dados</span>
                </button>

                <input 
                    type="file" 
                    id="fileInput" 
                    accept=".json,application/json" 
                    class="hidden"
                />
            </div>
        </div>

        {{-- ALERTAS DE FEEDBACK (SUCCESS / ERROR) --}}
        @if (session('success'))
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition
                class="fixed top-6 right-6 z-50 max-w-sm"
            >
                <div class="bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
                    <svg class="h-5 w-5 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-white/80 hover:text-white text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                x-transition
                class="fixed top-6 right-6 z-50 max-w-sm"
            >
                <div class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-lg dark:border-rose-900/50 dark:bg-rose-950 dark:text-rose-300">
                    <svg class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-rose-400 hover:text-rose-700 dark:text-rose-500 dark:hover:text-rose-300 text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        {{-- Trecho onde os erros são exibidos --}}
        @if ($errors->any())
            <div class="flex flex-col gap-2 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm dark:border-rose-900/50 dark:bg-rose-950 dark:text-rose-300">
                <div class="flex items-center gap-2 font-semibold text-sm">
                    <svg class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Erro na validação do arquivo de importação:</span>
                </div>
                <ul class="ml-7 list-disc text-xs sm:text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- RESUMO DE ESTATÍSTICAS / DIAGNÓSTICO --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Card 1: Linhas --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total de Linhas</p>
                    <p id="lineCount" class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100 mt-1 tabular-nums">0</p>
                </div>
                <div class="p-2.5 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
            </div>

            {{-- Card 2: Tamanho / Caracteres --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Tamanho do Conteúdo</p>
                    <p id="charCount" class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100 mt-1 tabular-nums">0 chars</p>
                </div>
                <div class="p-2.5 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3V6a2 2 0 012-2z" />
                    </svg>
                </div>
            </div>

            {{-- Card 3: Status de Validação --}}
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 bg-white dark:bg-neutral-900 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Validação da Estrutura</p>
                    <p id="status" class="text-2xl font-bold tracking-tight text-neutral-400 mt-1">—</p>
                </div>
                <div class="p-2.5 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- EDITOR DE CÓDIGO JSON --}}
        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-sm overflow-hidden flex flex-col">
            {{-- Barra superior do Editor --}}
            <div class="flex items-center justify-between px-4 py-2.5 bg-neutral-50 dark:bg-neutral-800/60 border-b border-neutral-200 dark:border-neutral-800 text-xs">
                <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span class="font-mono font-medium">payload.json</span>
                </div>
                <div id="jsonFeedback" class="font-medium text-xs"></div>
            </div>

            {{-- Área do Textarea --}}
            <div class="relative flex-1 p-2 sm:p-3">
                <textarea 
                    name="conteudo_importacao"
                    id="jsonInput"
                    rows="18"
                    class="w-full p-4 font-mono text-xs sm:text-sm rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-950 text-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 resize-y tracking-wide leading-relaxed"
                    placeholder='Cole aqui seu JSON ou use o botão "Carregar Arquivo"...'
                    spellcheck="false"
                ></textarea>
            </div>
        </div>

    </div>
</form>

<script>
(function () {
    const input = document.getElementById('jsonInput');
    const feedback = document.getElementById('jsonFeedback');
    const lineCount = document.getElementById('lineCount');
    const charCount = document.getElementById('charCount');
    const status = document.getElementById('status');
    const fileInput = document.getElementById('fileInput');
    const form = document.getElementById('formImportacao');
    const tipoImportacao = document.getElementById('tipoImportacao');

    function formatJson() {
        if (!input.value.trim()) return;
        try {
            const parsed = JSON.parse(input.value);
            input.value = JSON.stringify(parsed, null, 4);
            showSuccess('JSON formatado com sucesso');
            status.innerText = 'Válido';
            status.className = 'text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-1';
            updateStats();
        } catch (e) {
            showError('JSON inválido');
            status.innerText = 'Inválido';
            status.className = 'text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-1';
        }
    }

    function validateJson() {
        if (!input.value.trim()) {
            showError('Insira algum conteúdo JSON antes de validar');
            status.innerText = 'Vazio';
            status.className = 'text-2xl font-bold tracking-tight text-neutral-400 mt-1';
            return;
        }

        try {
            JSON.parse(input.value);
            showSuccess('Sintaxe JSON válida');
            status.innerText = 'Válido';
            status.className = 'text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-1';
        } catch (e) {
            showError('Erro de sintaxe: ' + e.message);
            status.innerText = 'Inválido';
            status.className = 'text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-1';
        }
    }

    function clearJson() {
        input.value = '';
        feedback.innerHTML = '';
        status.innerText = '—';
        status.className = 'text-2xl font-bold tracking-tight text-neutral-400 mt-1';
        updateStats();
    }

    function showSuccess(message) {
        feedback.innerHTML = `<span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">✓ ${message}</span>`;
    }

    function showError(message) {
        feedback.innerHTML = `<span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-medium">✕ ${message}</span>`;
    }

    function updateStats() {
        const text = input.value;
        lineCount.innerText = text ? text.split('\n').length : 0;
        charCount.innerText = text.length + ' chars';
    }

    // IMPORTAR ARQUIVO LOCAL
    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (!file) return;

        if (!file.name.endsWith('.json')) {
            showError('Selecione um arquivo .json válido');
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            try {
                const content = e.target.result;
                JSON.parse(content);
                input.value = content;
                showSuccess(`Arquivo "${file.name}" carregado`);
                status.innerText = 'Válido';
                status.className = 'text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-1';
                updateStats();
            } catch (err) {
                showError('O arquivo selecionado contém erros de sintaxe JSON');
                status.innerText = 'Inválido';
                status.className = 'text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-1';
            }
        };

        reader.readAsText(file);
    });

    // SUBMIT COM VALIDAÇÕES PRÉVIAS
    window.handleSubmit = function () {
        if (!tipoImportacao.value) {
            showError('Selecione o tipo de importação no menu');
            tipoImportacao.focus();
            return;
        }

        if (!input.value.trim()) {
            showError('O conteúdo JSON não pode estar vazio');
            input.focus();
            return;
        }

        try {
            JSON.parse(input.value);
        } catch (err) {
            showError('JSON inválido: ' + err.message);
            status.innerText = 'Inválido';
            status.className = 'text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-1';
            return;
        }

        const btnEnviar = document.querySelector('button[onclick="handleSubmit()"]');
        if (btnEnviar) {
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processando...
            `;
        }

        form.submit();
    };

    input.addEventListener('input', updateStats);

    window.formatJson = formatJson;
    window.validateJson = validateJson;
    window.clearJson = clearJson;

    updateStats();
})();
</script>

</x-layouts::app>