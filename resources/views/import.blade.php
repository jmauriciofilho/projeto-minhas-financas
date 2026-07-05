<x-layouts::app :title="__('Importacão')">

<form id="formImportacao" method="POST" action="{{ route('importacao.json') }}">
    @csrf

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- TOPO --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                Importação JSON
            </h1>

            <div class="flex items-center gap-2">

                <select 
                    name="tipo_importacao"
                    id="tipoImportacao"
                    class="px-3 py-2 text-sm rounded-lg border border-neutral-300 
                        dark:border-neutral-700 bg-white dark:bg-neutral-900 
                        text-neutral-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">Selecione o tipo</option>
                    <option value="classificacao">Classificação</option>
                    <option value="financeiro_mes">Financeiro Completo Mês</option>
                </select>

                <button type="button" onclick="formatJson()" 
                    class="px-4 py-2 bg-neutral-800 text-white text-sm rounded-lg hover:bg-neutral-700 transition">
                    Formatar
                </button>

                <button type="button" onclick="validateJson()" 
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-500 transition">
                    Validar
                </button>

                <button type="button" onclick="clearJson()" 
                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-500 transition">
                    Limpar
                </button>

                <button type="button" onclick="document.getElementById('fileInput').click()" 
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-500 transition">
                    Importar JSON
                </button>

                <button type="button" onclick="handleSubmit()"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-500 transition">
                    Enviar
                </button>

                <input 
                    type="file" 
                    id="fileInput" 
                    accept=".json,application/json" 
                    class="hidden"
                />

            </div>
        </div>


        {{-- FEEDBACK BACKEND --}}
        @if(session('success'))
            <div class="text-green-600 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="flex flex-col gap-2 rounded-xl border border-red-200
                    bg-red-50 p-4 text-red-700
                    dark:border-red-900/50 dark:bg-red-950 dark:text-red-300"
            >
                <div class="flex items-center gap-2 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0
                               9 9 0 0118 0z"/>
                    </svg>

                    <span>Não foi possível salvar a despesa</span>
                </div>

                <ul class="ml-6 list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- EDITOR --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

            <textarea 
                name="conteudo_importacao"
                id="jsonInput"
                class="w-full h-[500px] p-4 font-mono text-sm 
                       border border-neutral-200 dark:border-neutral-700 
                       rounded-lg 
                       bg-white dark:bg-neutral-900 
                       text-neutral-900 dark:text-white
                       focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder='Cole ou escreva seu JSON aqui...'
            ></textarea>

            {{-- FEEDBACK --}}
            <div id="jsonFeedback" class="mt-3 text-sm"></div>

        </div>


        {{-- STATS --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <p class="text-sm text-neutral-500">Linhas</p>
                <p id="lineCount" class="text-lg font-semibold mt-1">0</p>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <p class="text-sm text-neutral-500">Tamanho</p>
                <p id="charCount" class="text-lg font-semibold mt-1">0 chars</p>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <p class="text-sm text-neutral-500">Status</p>
                <p id="status" class="text-lg font-semibold mt-1">—</p>
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
        try {
            const parsed = JSON.parse(input.value);
            input.value = JSON.stringify(parsed, null, 4);
            showSuccess('JSON formatado com sucesso');
            updateStats();
        } catch (e) {
            showError('JSON inválido');
        }
    }

    function validateJson() {
        try {
            JSON.parse(input.value);
            showSuccess('JSON válido');
            status.innerText = 'Válido';
            status.className = 'text-lg font-semibold mt-1 text-green-600';
        } catch (e) {
            showError('Erro: ' + e.message);
            status.innerText = 'Inválido';
            status.className = 'text-lg font-semibold mt-1 text-red-600';
        }
    }

    function clearJson() {
        input.value = '';
        feedback.innerHTML = '';
        status.innerText = '—';
        updateStats();
    }

    function showSuccess(message) {
        feedback.innerHTML = `<span class="text-green-600">${message}</span>`;
    }

    function showError(message) {
        feedback.innerHTML = `<span class="text-red-600">${message}</span>`;
    }

    function updateStats() {
        const text = input.value;
        lineCount.innerText = text ? text.split('\n').length : 0;
        charCount.innerText = text.length + ' chars';
    }

    // IMPORTAR ARQUIVO
    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (!file) return;

        if (!file.name.endsWith('.json')) {
            showError('Selecione um arquivo JSON válido');
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            try {
                const content = e.target.result;

                JSON.parse(content);

                input.value = content;
                showSuccess('Arquivo importado com sucesso');
                updateStats();

            } catch (err) {
                showError('Arquivo JSON inválido');
            }
        };

        reader.readAsText(file);
    });

    // VALIDAR ANTES DE ENVIAR
    window.handleSubmit = function () {

        if (!tipoImportacao.value) {
            showError('Selecione o tipo de importação');
            return;
        }

        if (!input.value.trim()) {
            showError('JSON vazio');
            return;
        }

        try {
            JSON.parse(input.value);
        } catch (err) {
            showError('JSON inválido: ' + err.message);
            return;
        }

        const btnEnviar = document.querySelector('button[onclick="handleSubmit()"]');
        if (btnEnviar) {
            btnEnviar.disabled = true;
            btnEnviar.innerText = 'Enviando...';
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