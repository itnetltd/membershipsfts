<nav x-data="{ open: false }" class="bg-slate-950 border-b border-slate-800 text-white"> 
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        {{-- SFTS logo (PNG) --}}
                        <img src="{{ asset('images/sfts-logo.png') }}"
                             alt="Shoot For The Stars"
                             class="h-9 w-9 rounded-full bg-slate-900 p-0.5">
                        {{-- If you still want to use the SVG component, swap the img above with:
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        --}}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                class="text-slate-100 hover:text-yellow-300">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- FESTIVE CAMP: main user action --}}
                    <x-nav-link :href="route('festive-camp.register')" :active="request()->routeIs('festive-camp.*')"
                                class="text-slate-100 hover:text-yellow-300">
                        {{ __('Festive Camp Registration') }}
                    </x-nav-link>

                    {{-- TEMPORARILY DISABLED: My Application (normal membership)
                    <x-nav-link :href="route('application.create')" :active="request()->routeIs('application.*')"
                                class="text-slate-100 hover:text-yellow-300">
                        {{ __('My Application') }}
                    </x-nav-link>
                    --}}

                    {{-- Member: Billing (show only to NON-admins) --}}
                    @auth
                        @php $isAdmin = auth()->user()?->is_admin ?? false; @endphp
                        @unless($isAdmin)
                            {{-- TEMPORARILY DISABLED: normal billing for membership
                            <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')"
                                        class="text-slate-100 hover:text-yellow-300">
                                {{ __('Billing') }}
                            </x-nav-link>
                            --}}
                        @endunless
                    @endauth

                    {{-- Admin links --}}
                    @auth
                        @if($isAdmin)
                            <x-nav-link :href="route('admin.apps.index')" :active="request()->routeIs('admin.apps.*')"
                                        class="text-slate-100 hover:text-yellow-300">
                                {{ __('Admin Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.payments.index')" :active="request()->routeIs('admin.payments.*')"
                                        class="text-slate-100 hover:text-yellow-300">
                                {{ __('Admin: Payments') }}
                            </x-nav-link>
                            {{-- Admin view of festive camp campers --}}
                            <x-nav-link :href="route('admin.festive-camp.index')" :active="request()->routeIs('admin.festive-camp.*')"
                                        class="text-slate-100 hover:text-yellow-300">
                                {{ __('Admin: Festive Camp') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    <!-- Profile -->
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                                class="text-slate-100 hover:text-yellow-300">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-medium rounded-md text-slate-100 bg-slate-900 hover:bg-slate-800 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-slate-200 hover:text-white hover:bg-slate-800 focus:outline-none focus:bg-slate-800 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-950 text-slate-100 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- FESTIVE CAMP: main action on mobile --}}
            <x-responsive-nav-link :href="route('festive-camp.register')" :active="request()->routeIs('festive-camp.*')">
                {{ __('Festive Camp Registration') }}
            </x-responsive-nav-link>

            {{-- TEMPORARILY DISABLED: My Application
            <x-responsive-nav-link :href="route('application.create')" :active="request()->routeIs('application.*')">
                {{ __('My Application') }}
            </x-responsive-nav-link>
            --}}

            {{-- Member: Billing (only non-admins) --}}
            @auth
                @php $isAdmin = auth()->user()?->is_admin ?? false; @endphp
                @unless($isAdmin)
                    {{-- TEMPORARILY DISABLED: normal billing
                    <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                        {{ __('Billing') }}
                    </x-responsive-nav-link>
                    --}}
                @endunless
            @endauth

            {{-- Admin links --}}
            @auth
                @if($isAdmin)
                    <x-responsive-nav-link :href="route('admin.apps.index')" :active="request()->routeIs('admin.apps.*')">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.payments.index')" :active="request()->routeIs('admin.payments.*')">
                        {{ __('Admin: Payments') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.festive-camp.index')" :active="request()->routeIs('admin.festive-camp.*')">
                        {{ __('Admin: Festive Camp') }}
                    </x-responsive-nav-link>
                @endif
            @endauth

            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-slate-50">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
