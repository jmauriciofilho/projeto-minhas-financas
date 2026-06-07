<x-layouts::app :title="__('Editar Despesa')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl max-w-xl">

        {{-- Cabeçalho --}}
        <div>
            <h1 class="text-xl font-semibold text-neutral-900 dark:text-white">
                Editar Despesa
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Altere os dados permitidos da despesa
            </p>
        </div>

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

                    <span>Não foi possível atualizar a despesa</span>
                </div>

                <ul class="ml-6 list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário --}}
        <form
            method="POST"
            action="{{ route('despesas.update', $despesa) }}"
            class="flex flex-col gap-5 rounded-xl border border-neutral-200
                   bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            @csrf
            @method('PUT')

            {{-- Descrição --}}
            <div class="flex flex-col gap-1">
                <label
                    for="descricao"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Descrição
                </label>

                <input
                    id="descricao"
                    name="nome"
                    type="text"
                    placeholder="Ex: Luz, Aluguel, Supermercado..."
                    value="{{ old('nome', $despesa->nome) }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 placeholder:text-neutral-400
                           focus:border-green-600 focus:ring-green-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white dark:placeholder:text-neutral-500"
                    required
                />
            </div>

            {{-- Conta --}}
            <div class="flex flex-col gap-1">
                <label
                    for="conta_id"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Conta
                </label>

                <select
                    id="conta_id"
                    name="conta_id"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                        text-neutral-900
                        focus:border-green-600 focus:ring-green-600
                        dark:border-neutral-700 dark:bg-neutral-800
                        dark:text-white"
                    required
                >
                    <option value="">Selecione uma conta</option>

                    @foreach ($contas as $conta)
                        <option 
                            value="{{ $conta->id }}"
                            {{ old('conta_id', $despesa->conta_id) == $conta->id ? 'selected' : '' }}
                        >
                            {{ $conta->nome }} 
                            (Saldo: R$ {{ number_format($conta->saldo, 2, ',', '.') }})
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Valor --}}
            <div class="flex flex-col gap-1">
                <label
                    for="valor"
                    class="text-sm font-medium text-neutral-700 dark:text-neutral-300"
                >
                    Valor
                </label>

                <input
                    id="valor"
                    name="valor"
                    type="number"
                    step="0.01"
                    placeholder="0,00"
                    value="{{ old('valor', $despesa->valor) }}"
                    class="rounded-lg border border-neutral-300 bg-white px-3 py-2
                           text-neutral-900 placeholder:text-neutral-400
                           focus:border-green-600 focus:ring-green-600
                           dark:border-neutral-700 dark:bg-neutral-800
                           dark:text-white dark:placeholder:text-neutral-500"
                    required
                />
            </div>

            {{-- Ações --}}
            <div class="mt-4 flex justify-end gap-3">
                <a
                    href="{{ route('despesas.index') }}"
                    class="rounded-lg border border-neutral-300 px-4 py-2
                           text-neutral-700 hover:bg-neutral-100
                           dark:border-neutral-700 dark:text-neutral-300
                           dark:hover:bg-neutral-800"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-green-600 px-4 py-2
                           font-medium text-white hover:bg-green-700"
                >
                    Atualizar Despesa
                </button>
            </div>

        </form>

    </div>
</x-layouts::app>