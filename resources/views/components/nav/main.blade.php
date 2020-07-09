<nav x-data="{ open: false }" @keydown.window.escape="open = false" class="bg-gray-800"
     xmlns:x-bind="http://www.w3.org/1999/xhtml" xmlns:x-transition="http://www.w3.org/1999/xhtml">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="border-b border-gray-700">
            <div class="flex items-center justify-between h-16 px-4 sm:px-0">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/">
                            <x-svg.logo class="w-10 h-10 text-white"></x-svg.logo>
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline">
                            @if(is_active('castle.*'))
                                <x-nav.link :href="route('home')" class="flex self-center">
                                    <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Leave Castle')
                                </x-nav.link>

                                <x-nav.link :href="route('castle.dashboard')" class="ml-4"
                                            :active="is_active('castle.dashboard')">
                                    @lang('Dashboard')
                                </x-nav.link>

                                <x-nav.link :href="route('castle.masters.index')" class="ml-4"
                                            :active="is_active('castle.masters.*')">
                                    @lang('Masters')
                                </x-nav.link>

                                <x-nav.link :href="route('castle.users.index')" class="ml-4"
                                            :active="is_active('castle.users.*')">
                                    @lang('Users')
                                </x-nav.link>
                            @else
                                <x-nav.link :href="route('home')" class="ml-4"
                                            :active="is_active('home')">
                                    @lang('Dashboard')
                                </x-nav.link>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        @if(user()->isMaster())
                            <x-nav.castle-icon/>
                        @endif

                        <button
                            class="p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-gray-700"
                            aria-label="Notifications">
                            <x-svg.notification/>
                        </button>
                        <div @click.away="open = false" class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open"
                                        class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid"
                                        id="user-menu" aria-label="User menu" aria-haspopup="true"
                                        x-bind:aria-expanded="open">
                                    <img class="h-8 w-8 rounded-full"
                                         src="{{ user()->photo_url }}"
                                         alt=""/>
                                </button>
                            </div>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg z-10">
                                <div class="py-1 rounded-md bg-white shadow-xs">

                                    <span class="block px-4 pt-2 text-sm text-gray-600">
                                        {{ user()->name }}
                                    </span>
                                    <span class="block px-4 pb-2 pt-0.5 text-xs text-gray-500">
                                        {{ user()->email }}
                                    </span>

                                    <hr class="my-2">

                                    <a href="{{ route('profile') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        @lang('Your Profile')
                                    </a>

                                    <button type="submit" form="form-sign-out"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                        @lang('Sign out')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    @if(user()->isMaster())
                        <x-nav.castle-icon/>
                    @endif

                    <button
                        class="p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-gray-700"
                        aria-label="Notifications">
                        <x-svg.notification/>
                    </button>

                    <button @click="open = !open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white"
                            x-bind:aria-label="open ? 'Close main menu' : 'Main menu'"
                            x-bind:aria-expanded="open">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div :class="{'block': open, 'hidden': !open}" class="hidden border-b border-gray-700 md:hidden">
        <div class="px-2 py-3 sm:px-3">
            @if(is_active('castle.*'))
                <x-nav.link-mobile :href="route('home')" class="flex self-center">
                    <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Leave Castle')
                </x-nav.link-mobile>

                <x-nav.link-mobile :href="route('castle.dashboard')" :active="is_active('castle.dashboard')"
                                   class="mt-1">
                    @lang('Dashboard')
                </x-nav.link-mobile>

                <x-nav.link-mobile :href="route('castle.masters.index')" class="mt-1"
                                   :active="is_active('castle.masters.*')">
                    @lang('Masters')
                </x-nav.link-mobile>

                <x-nav.link-mobile :href="route('castle.users.index')" class="mt-1"
                                   :active="is_active('castle.users.*')">
                    @lang('Users')
                </x-nav.link-mobile>
            @else
                <x-nav.link-mobile :href="route('home')" :active="is_active('home')">
                    @lang('Dashboard')
                </x-nav.link-mobile>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full"
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                         alt=""/>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-white">
                        {{ user()->name }}
                    </div>
                    <div class="mt-1 text-sm font-medium leading-none text-gray-400">
                        {{ user()->email }}
                    </div>
                </div>
            </div>
            <div class="mt-3 px-2" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                <a href="{{ route('profile') }}"
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                   role="menuitem">
                    @lang('Your Profile')
                </a>
                <button type="submit" form="form-sign-out"
                        class="w-full text-left mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                        role="menuitem">
                    @lang('Sign out')
                </button>
            </div>
        </div>
    </div>
    <x-form id="form-sign-out" :route="route('logout')" class="hidden"></x-form>
</nav>
