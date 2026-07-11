@props([
    'sidebar' => false,
])

@if($sidebar)
    {{-- Versão para Sidebar --}}
    <flux:sidebar.brand name="Minhas Finanças" {{ $attributes }}>
        {{-- Adicionamos classes para forçar a cor padrão da imagem e isolar do CSS do Flux --}}
        <x-slot name="logo" class="!h-auto !w-auto flex items-center justify-center overflow-visible text-current">
            
            <img 
                src="{{ asset('icon-mfp.png') }}" 
                alt="Logo Minhas Finanças"
                {{-- !text-transparent remove interferências de cores de texto do Flux --}}
                {{-- mix-blend-normal garante renderização padrão de cores --}}
                class="h-9 w-auto flex-shrink-0 object-contain block !text-transparent mix-blend-normal"
            />

        </x-slot>
    </flux:sidebar.brand>
@else
    {{-- Versão para Topo de Página (Brand normal) --}}
    <flux:brand name="Minhas Finanças" {{ $attributes }}>
        <x-slot name="logo" class="!h-auto !w-auto flex items-center justify-center overflow-visible text-current">
            
            <img 
                src="{{ asset('icon-mfp.png') }}" 
                alt="Logo Minhas Finanças"
                class="h-9 w-auto flex-shrink-0 object-contain block !text-transparent mix-blend-normal"
            />

        </x-slot>
    </flux:brand>
@endif