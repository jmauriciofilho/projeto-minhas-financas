@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Minhas Finanças" {{ $attributes }}>
        <x-slot name="logo" 
            class="flex aspect-square size-8 items-center justify-center rounded-md bg-emerald-500 text-white">

            <svg xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 24 24" 
                fill="none" 
                stroke="currentColor" 
                stroke-width="2" 
                class="size-5">
                <rect x="2" y="6" width="20" height="12" rx="2"/>
                <circle cx="16" cy="12" r="1.5"/>
            </svg>

        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Minhas Finanças" {{ $attributes }}>
        <x-slot name="logo" 
            class="flex aspect-square size-8 items-center justify-center rounded-md bg-emerald-500 text-white">

            <svg xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 24 24" 
                fill="none" 
                stroke="currentColor" 
                stroke-width="2" 
                class="size-5">
                <rect x="2" y="6" width="20" height="12" rx="2"/>
                <circle cx="16" cy="12" r="1.5"/>
            </svg>

        </x-slot>
    </flux:brand>
@endif
