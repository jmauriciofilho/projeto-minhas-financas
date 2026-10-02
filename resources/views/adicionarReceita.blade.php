<x-layouts::app :title="__('Adicionar Receita')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 max-w-2xl mx-auto">

        {{-- TOPO: RETORNO + CABEÇALHO --}}
        <div>
            <a 
                href="{{ route('receitas.index') }}" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200 transition mb-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar para Receitas
            </a>

            <div class="flex items-center gap-3 mt-1">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Adicionar Receita
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Registre um novo rendimento, salário ou entrada financeira
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
                    <span>Não foi possível salvar a receita:</span>
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
            action="{{ route('receitas.store') }}"
            class="flex flex-col gap-6 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 sm:p-8 shadow-sm"
        >
            @csrf

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
                        placeholder="Ex: Salário, Freelancer, Venda de item..."
                        value="{{ old('nome') }}"
                        class="w-full rounded-xl border @error('nome') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                        required
                    />
                    @error('nome')
                        <span class="text-xs text-rose-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 2. Linha Dupla: Conta de Destino e Valor --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Conta --}}
                    <div class="flex flex-col gap-1.5">
                        <label
                            for="conta_id"
                            class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                        >
                            Conta de Destino <span class="text-rose-500">*</span>
                        </label>

                        <select
                            id="conta_id"
                            name="conta_id"
                            class="w-full rounded-xl border @error('conta_id') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                            required
                        >
                            <option value="" disabled {{ old('conta_id') ? '' : 'selected' }}>Selecione a conta...</option>
                            @foreach ($contas as $conta)
                                <option 
                                    value="{{ $conta->id }}"
                                    @selected(old('conta_id') == $conta->id)
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
                                value="{{ old('valor') }}"
                                class="w-full rounded-xl border @error('valor') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 pl-10 pr-3.5 py-2.5 text-sm font-mono font-semibold text-emerald-600 dark:text-emerald-400 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition tabular-nums"
                                required
                            />
                        </div>
                        @error('valor')
                            <span class="text-xs text-rose-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 3. Linha Dupla: Mês da Receita e Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Mês da Receita --}}
                    <div class="flex flex-col gap-1.5">
                        <label
                            for="mes"
                            class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                        >
                            Mês de Referência <span class="text-rose-500">*</span>
                        </label>

                        <select
                            id="mes"
                            name="mes"
                            class="w-full rounded-xl border @error('mes') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                            required
                        >
                            @php
                                $mesAtual = old('mes', now()->format('Y-m'));
                            @endphp

                            @for ($i = -6; $i <= 6; $i++)
                                @php
                                    $data = now()->addMonths($i);
                                    $numeroMes = $data->format('Y-m');
                                    $label = $data->format('m/Y');
                                @endphp

                                <option 
                                    value="{{ $numeroMes }}"
                                    @selected($mesAtual == $numeroMes)
                                >
                                    {{ $label }}
                                </option>
                            @endfor
                        </select>
                        @error('mes')
                            <span class="text-xs text-rose-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status da Receita --}}
                    <div class="flex flex-col gap-1.5">
                        <label
                            for="status"
                            class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                        >
                            Status do Recebimento <span class="text-rose-500">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-xl border @error('status') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                            required
                        >
                            <option value="pendente" @selected(old('status', 'pendente') == 'pendente')>
                                Pendente (Ainda não recebido)
                            </option>
                            <option value="recebido" @selected(old('status') == 'recebido')>
                                Recebido (Crédito imediato em conta)
                            </option>
                        </select>
                        @error('status')
                            <span class="text-xs text-rose-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- BOTÕES DE AÇÃO --}}
            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800/80 flex items-center justify-end gap-3">
                <a
                    href="{{ route('receitas.index') }}"
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
                    <span>Salvar Receita</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>