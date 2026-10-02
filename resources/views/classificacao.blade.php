@php
    $corInicial = old('background_color', $classificacao->background_color ?? '#3b82f6');
@endphp

<x-layouts::app :title="isset($classificacao) ? __('Editar Classificação') : __('Nova Classificação')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO CONTEXTUAL --}}
        <div>
            <a 
                href="{{ route('classificacoes.index') }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Classificações
            </a>

            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ isset($classificacao) ? 'Editar Classificação' : 'Nova Classificação' }}
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Defina o nome e a cor de identificação para categorizar seus lançamentos
                    </p>
                </div>
            </div>
        </div>

        {{-- ALERTA DE ERROS DE VALIDAÇÃO GERAL --}}
        @if ($errors->any())
            <div class="flex flex-col gap-2 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm dark:border-rose-900/50 dark:bg-rose-950 dark:text-rose-300">
                <div class="flex items-center gap-2 font-semibold text-sm">
                    <svg class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Não foi possível salvar a classificação:</span>
                </div>
                <ul class="ml-7 list-disc text-xs sm:text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULÁRIO --}}
        <form
            method="POST"
            action="{{ isset($classificacao) ? route('classificacoes.update', $classificacao) : route('classificacoes.store') }}"
            class="flex flex-col gap-5 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
        >
            @csrf
            @if(isset($classificacao))
                @method('PUT')
            @endif

            {{-- NOME DA CLASSIFICAÇÃO --}}
            <div class="flex flex-col gap-1.5">
                <label for="nome" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Nome da Classificação
                </label>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>

                    <input
                        id="nome"
                        name="nome"
                        type="text"
                        value="{{ old('nome', $classificacao->nome ?? '') }}"
                        placeholder="Ex: Alimentação, Moradia, Lazer, Saúde..."
                        class="w-full rounded-xl border pl-10 pr-3.5 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 bg-white dark:bg-neutral-800 dark:text-white dark:placeholder:text-neutral-500 shadow-sm transition
                               @error('nome') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @else border-neutral-300 dark:border-neutral-700 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 @enderror"
                        required
                    />
                </div>
                @error('nome')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- COR DE BACKGROUND / IDENTIFICADOR --}}
            <div class="flex flex-col gap-2">
                <label for="background_color" class="text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Cor de Identificação
                </label>

                {{-- Campo Color Picker + Código Hex + Preview --}}
                <div class="p-4 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-800/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="relative flex items-center">
                            <input
                                id="background_color"
                                name="background_color"
                                type="color"
                                value="{{ $corInicial }}"
                                class="h-11 w-12 cursor-pointer rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-1 shadow-sm transition"
                                required
                            />
                        </div>

                        <div>
                            <span class="text-xs text-neutral-400 block font-medium">Código Hexadecimal</span>
                            <span id="hex-label" class="font-mono text-sm font-semibold text-neutral-800 dark:text-neutral-200 uppercase">
                                {{ $corInicial }}
                            </span>
                        </div>
                    </div>

                    {{-- Preview ao vivo em formato de Badge --}}
                    <div class="flex items-center gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-neutral-200 dark:border-neutral-800">
                        <span class="text-xs text-neutral-400 font-medium">Prévia:</span>
                        <span 
                            id="preview-badge" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-white shadow-xs transition-all"
                            style="background-color: {{ $corInicial }}"
                        >
                            <span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>
                            <span id="preview-text">{{ old('nome', $classificacao->nome ?? 'Exemplo de Categoria') }}</span>
                        </span>
                    </div>
                </div>

                {{-- Paleta de Cores Rápidas --}}
                <div class="flex flex-col gap-1.5 mt-1">
                    <span class="text-[11px] text-neutral-400 dark:text-neutral-500">Ou escolha uma sugestão rápida:</span>
                    <div class="flex flex-wrap items-center gap-2">
                        @php
                            $coresSugeridas = [
                                '#10b981', // Emerald
                                '#3b82f6', // Blue
                                '#6366f1', // Indigo
                                '#8b5cf6', // Purple
                                '#ec4899', // Pink
                                '#f43f5e', // Rose
                                '#f59e0b', // Amber
                                '#06b6d4', // Cyan
                                '#64748b', // Slate
                            ];
                        @endphp
                        @foreach ($coresSugeridas as $cor)
                            <button
                                type="button"
                                onclick="definirCor('{{ $cor }}')"
                                class="w-6 h-6 rounded-full border border-black/10 dark:border-white/10 hover:scale-110 active:scale-95 transition-transform shadow-xs"
                                style="background-color: {{ $cor }}"
                                title="{{ $cor }}"
                            ></button>
                        @endforeach
                    </div>
                </div>

                @error('background_color')
                    <span class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</span>
                @enderror
            </div>

            {{-- AÇÕES DO FORMULÁRIO --}}
            <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-end gap-3">
                <a
                    href="{{ route('classificacoes.index') }}"
                    class="px-4 py-2 text-xs sm:text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-xl transition"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-medium rounded-xl shadow-sm transition-all"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ isset($classificacao) ? 'Salvar Alterações' : 'Salvar Classificação' }}</span>
                </button>
            </div>
        </form>

    </div>

    <script>
        (function () {
            const inputNome = document.getElementById('nome');
            const inputCor = document.getElementById('background_color');
            const hexLabel = document.getElementById('hex-label');
            const previewBadge = document.getElementById('preview-badge');
            const previewText = document.getElementById('preview-text');

            function atualizarPreview() {
                if (inputCor && hexLabel && previewBadge) {
                    const cor = inputCor.value;
                    hexLabel.innerText = cor.toUpperCase();
                    previewBadge.style.backgroundColor = cor;
                }

                if (inputNome && previewText) {
                    const nome = inputNome.value.trim();
                    previewText.innerText = nome || 'Exemplo de Categoria';
                }
            }

            window.definirCor = function (cor) {
                if (inputCor) {
                    inputCor.value = cor;
                    atualizarPreview();
                }
            };

            if (inputCor) {
                inputCor.addEventListener('input', atualizarPreview);
            }

            if (inputNome) {
                inputNome.addEventListener('input', atualizarPreview);
            }
        })();
    </script>
</x-layouts::app>