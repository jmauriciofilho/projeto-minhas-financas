<x-layouts::app :title="__('Adicionar Conta')">
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
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Adicionar Conta
                    </h1>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Cadastre uma conta bancária, carteira física ou conta benefício
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
                    <span>Não foi possível salvar a conta:</span>
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
            action="{{ route('adicionar.conta') }}"
            class="flex flex-col gap-6 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 sm:p-8 shadow-sm"
        >
            @csrf

            <div class="space-y-5">
                {{-- 1. Nome da Conta --}}
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
                        value="{{ old('nome') }}"
                        placeholder="Ex: Nubank, Itaú, Carteira..."
                        class="w-full rounded-xl border @error('nome') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                        required
                    />
                    @error('nome')
                        <span class="text-xs text-rose-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 2. Tipo da Conta --}}
                <div class="flex flex-col gap-1.5">
                    <label
                        for="tipo"
                        class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                    >
                        Tipo da Conta <span class="text-rose-500">*</span>
                    </label>

                    <select
                        id="tipo"
                        name="tipo"
                        class="w-full rounded-xl border @error('tipo') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 px-3.5 py-2.5 text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                        required
                    >
                        <option value="" disabled {{ old('tipo') ? '' : 'selected' }}>Selecione a finalidade da conta...</option>
                        <option value="CORRENTE" @selected(old('tipo') === 'CORRENTE')>Conta Corrente / Carteira</option>
                        <option value="BENEFICIO" @selected(old('tipo') === 'BENEFICIO')>Benefício (Vale Alimentação/Refeição)</option>
                    </select>
                    @error('tipo')
                        <span class="text-xs text-rose-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 3. Saldo Inicial --}}
                <div class="flex flex-col gap-1.5">
                    <label
                        for="balance"
                        class="text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300"
                    >
                        Saldo Inicial
                    </label>

                    <div class="relative rounded-xl shadow-xs">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400 text-sm font-semibold">
                            R$
                        </div>
                        <input
                            id="balance"
                            name="saldo"
                            type="number"
                            step="0.01"
                            value="{{ old('saldo', '0.00') }}"
                            placeholder="0,00"
                            class="w-full rounded-xl border @error('saldo') border-rose-500 @else border-neutral-300 dark:border-neutral-700 @enderror bg-white dark:bg-neutral-800 pl-10 pr-3.5 py-2.5 text-sm font-mono text-neutral-900 dark:text-neutral-100 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 transition"
                        />
                    </div>
                    <span class="text-[11px] text-neutral-400">Informe o saldo em conta no momento do cadastro (pode ser negativo se estiver no cheque especial).</span>
                    @error('saldo')
                        <span class="text-xs text-rose-500">{{ $message }}</span>
                    @enderror
                </div>
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
                    <span>Salvar Conta</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>