@props(['mes' => null])

@php
    $dataCarbon = \Carbon\Carbon::parse(($mes ?? now()->format('Y-m')) . '-01');
    $mesAnterior = $dataCarbon->copy()->subMonth()->format('Y-m');
    $mesSeguinte = $dataCarbon->copy()->addMonth()->format('Y-m');
    $mesAtual = now()->format('Y-m');
    $isMesAtual = ($mes ?? $mesAtual) === $mesAtual;
@endphp

<div class="flex items-center gap-1.5">
    <div class="inline-flex items-center p-0.5 rounded-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-sm">
        <a href="{{ request()->fullUrlWithQuery(['mes' => $mesAnterior]) }}" class="p-1.5 rounded-lg text-neutral-400 hover:text-neutral-800 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <form action="{{ url()->current() }}" method="GET" class="relative flex items-center">
            @foreach(request()->except('mes') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <input 
                type="month"
                name="mes"
                value="{{ $mes ?? $mesAtual }}"
                onchange="this.form.submit()"
                class="px-2 py-1 text-xs sm:text-sm font-semibold text-neutral-800 dark:text-neutral-200 bg-transparent border-0 focus:ring-0 cursor-pointer text-center"
            >
        </form>

        <a href="{{ request()->fullUrlWithQuery(['mes' => $mesSeguinte]) }}" class="p-1.5 rounded-lg text-neutral-400 hover:text-neutral-800 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if(!$isMesAtual)
        <a href="{{ request()->fullUrlWithQuery(['mes' => $mesAtual]) }}" class="px-2.5 py-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl transition">
            Hoje
        </a>
    @endif
</div>