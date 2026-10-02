<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        {{-- SIDEBAR FLUX (DESKTOP & DRAWER MOBILE) --}}
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                {{-- GRUPO 1: VISÃO GERAL --}}
                <flux:sidebar.group :heading="__('Visão Geral')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calendar-date-range" :href="route('visaoMes')" :current="request()->routeIs('visaoMes')" wire:navigate>
                        {{ __('Visão Mês') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- GRUPO 2: MOVIMENTAÇÕES & FINANÇAS --}}
                <flux:sidebar.group :heading="__('Finanças')" class="grid mt-2">
                    <flux:sidebar.item icon="wallet" :href="route('contas')" :current="request()->routeIs('contas*') || request()->routeIs('*conta*')" wire:navigate>
                        {{ __('Contas') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-trending-up" :href="route('receitas.index')" :current="request()->routeIs('receitas.*')" wire:navigate>
                        {{ __('Receitas') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-trending-down" :href="route('despesas.index')" :current="request()->routeIs('despesas.*')" wire:navigate>
                        {{ __('Despesas') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="credit-card" :href="route('cartoes.index')" :current="request()->routeIs('cartoes.*')" wire:navigate>
                        {{ __('Cartões') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            {{-- GRUPO 3: CADASTROS & DADOS --}}
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Sistema')" class="grid">
                    <flux:sidebar.item icon="tag" :href="route('classificacoes.index')" :current="request()->routeIs('classificacoes.*')" wire:navigate>
                        {{ __('Classificações') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-up-tray" :href="route('importacao')" :current="request()->routeIs('importacao*')" wire:navigate>
                        {{ __('Importação') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- HEADER MOBILE -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Configurações') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer text-rose-600 dark:text-rose-400"
                            data-test="logout-button"
                        >
                            {{ __('Sair') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>