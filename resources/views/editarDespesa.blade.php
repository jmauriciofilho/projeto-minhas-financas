<x-layouts::app :title="__('Editar Despesa')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-2xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO --}}
        <div>
            <a 
                href="{{ route('despesas.index') }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Despesas
            </a>

            <div class="flex items-center gap-3 mt-1">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Editar Despesa
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Atualize os dados permitidos da despesa <strong class="text-neutral-700 dark:text-neutral-300">{{ $despesa->nome }}</strong>
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
                    <span>Não foi possível atualizar a despesa:</span>
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
            action="{{ route('despesas.update', $despesa) }}"
            class="flex flex-col gap-6 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 sm:p-8 shadow-sm"
        >
            @csrf
            @method('PUT')

            {{-- Informações de Contexto Atual da Despesa --}}
            <div class="p-4 rounded-xl bg-neutral-50 dark:bg-neutral-800/40 border border-neutral-100 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <span class="text-xs text-neutral-400 block">Status do Pagamento</span>
                    @if($despesa->ja_pago)
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Pago
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Pendente
                        </span>
                    @endif
                </div>

                <div class="text-right">
                    <span class="text-xs text-neutral-400 block">Mês / Tipo</span>
                    <div class="flex items-center gap-1.5 justify-end mt-1">
                        @if($despesa->recorrente)
                            <span class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-900">
                                Recorrente
                            </span>
                        @endif
                        <span class="text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                            {{ $despesa->mes ? \Carbon\Carbon::parse($despesa->mes)->format('m/Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                {{-- 1. Descrição --}}
                <div class="flex flex-col gap-1.5">
                    <label
                        for="descricao"
                        class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                    >
                        Descrição <span class="text-rose-500">*</span>
                    </label>

                    <input
                        id="descricao"
                        name="nome"
                        type="text"
                        placeholder="Ex: Luz, Aluguel, Supermercado..."
                        value="{{ old('nome', $despesa->nome) }}"
                        class="w-full rounded-xl border @error('nome') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                        required
                    />
                    @error('nome')
                        <span class="text-xs text-rose-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 2. Linha Dupla: Conta de Débito e Valor --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Conta --}}
                    <div class="flex flex-col gap-1.5">
                        <label
                            for="conta_id"
                            class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                        >
                            Conta de Débito <span class="text-rose-500">*</span>
                        </label>

                        <select
                            id="conta_id"
                            name="conta_id"
                            class="w-full rounded-xl border @error('conta_id') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                            required
                        >
                            <option value="" disabled>Selecione uma conta...</option>
                            @foreach ($contas as $conta)
                                <option 
                                    value="{{ $conta->id }}"
                                    @selected(old('conta_id', $despesa->conta_id) == $conta->id)
                                >
                                    {{ $conta->nome }} (Saldo: R$ {{ number_format($conta->saldo, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('conta_id')
                            <span class="text-xs text-rose-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Valor --}}
                    <div class="flex flex-col gap-1.5">
                        <label
                            for="valor"
                            class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                        >
                            Valor <span class="text-rose-500">*</span>
                        </label>

                        <div class="relative rounded-xl shadow-xs">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400 text-sm font-semibold">
                                R$
                            </div>
                            <input
                                id="valor"
                                name="valor"
                                type="number"
                                step="0.01"
                                placeholder="0,00"
                                value="{{ old('valor', $despesa->valor) }}"
                                class="w-full rounded-xl border @error('valor') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 pl-10 pr-3.5 py-2.5 text-sm font-mono font-semibold text-rose-600 dark:text-rose-400 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition tabular-nums"
                                required
                            />
                        </div>
                        @error('valor')
                            <span class="text-xs text-rose-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- BOTÕES DE AÇÃO --}}
            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800/80 flex items-center justify-end gap-3">
                <a
                    href="{{ route('despesas.index') }}"
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
                    <span>Atualizar Despesa</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>