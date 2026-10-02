<x-layouts::app :title="__('Editar Conta')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-2xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO --}}
        <div>
            <a 
                href="{{ route('contas') }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Contas
            </a>

            <div class="flex items-center gap-3 mt-1">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Editar Conta
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Atualize a identificação da conta <strong class="text-neutral-700 dark:text-neutral-300">{{ $conta->nome }}</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- ALERTA DE ERROS DO FORMULÁRIO --}}
        @if ($errors->any())
            <div class="flex flex-col gap-2 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm dark:border-rose-900/50 dark:bg-rose-950 dark:text-rose-300">
                <div class="flex items-center gap-2 font-semibold text-sm">
                    <svg class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Não foi possível atualizar a conta:</span>
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
            action="{{ route('atualizar.conta', $conta) }}"
            class="flex flex-col gap-6 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 sm:p-8 shadow-sm"
        >
            @csrf
            @method('PUT')

            {{-- Informações de Contexto da Conta --}}
            <div class="p-4 rounded-xl bg-neutral-50 dark:bg-neutral-800/40 border border-neutral-100 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <span class="text-xs text-neutral-400 block">Saldo Registrado</span>
                    <span class="text-base sm:text-lg font-bold tabular-nums {{ ($conta->saldo ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        R$ {{ number_format($conta->saldo ?? 0, 2, ',', '.') }}
                    </span>
                </div>

                @if(!empty($conta->tipo))
                    <div class="text-right">
                        <span class="text-xs text-neutral-400 block">Finalidade</span>
                        <span class="inline-flex items-center mt-0.5 px-2.5 py-0.5 rounded-md text-xs font-medium bg-neutral-200/80 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-200">
                            {{ ucfirst($conta->tipo) }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Campo: Nome da Conta --}}
            <div class="flex flex-col gap-1.5">
                <label
                    for="name"
                    class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                >
                    Nome da Conta <span class="text-rose-500">*</span>
                </label>

                <input
                    id="name"
                    name="nome"
                    type="text"
                    value="{{ old('nome', $conta->nome) }}"
                    placeholder="Ex: Nubank, Itaú, Carteira..."
                    class="w-full rounded-xl border @error('nome') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                    required
                />
                @error('nome')
                    <span class="text-xs text-rose-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- BOTÕES DE AÇÃO --}}
            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800/80 flex items-center justify-end gap-3">
                <a
                    href="{{ route('contas') }}"
                    class="px-4 py-2.5 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-xl transition"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Salvar Alterações</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>