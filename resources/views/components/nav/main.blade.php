<div class="bg-white">
    <nav x-data="{ open: false }" @keydown.window.escape="open = false" class="bg-white border-b border-gray-200"
        xmlns:x-bind="http://www.w3.org/1999/xhtml" xmlns:x-transition="http://www.w3.org/1999/xhtml">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 hidden md:block">
            <div class="flex items-center justify-between h-16 px-4 sm:px-0">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/">
                            <x-svg.logo class="w-10 h-10 text-green-base"></x-svg.logo>
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <div class="hidden mr-3 sm:-my-px sm:ml-6 sm:flex">
                                @if(is_active('castle.*'))
                                    <x-nav.link :href="route('home')" class="flex self-center">
                                        <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Leave Admin')
                                    </x-nav.link>
    
                                    <x-nav.link :href="route('castle.dashboard')" class="ml-4"
                                                :active="is_active('castle.dashboard')">
                                        @lang('Dashboard')
                                    </x-nav.link>

                                    <x-nav.link :href="route('castle.permission.index')" class="ml-4"
                                                :active="is_active('castle.permission.*')">
                                        @lang('Permission')
                                    </x-nav.link>
    
                                    <x-nav.link :href="route('castle.incentives')" class="ml-4"
                                                :active="is_active('castle.incentives')">
                                        @lang('Incentives')
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
                                    <x-nav.link :href="route('scoreboard')" class="ml-8"
                                                :active="is_active('scoreboard')">
                                        @lang('Scoreboard')
                                    </x-nav.link>
                                    <x-nav.link :href="route('trainings.index')" class="ml-8"
                                                :active="is_active('trainings.*')">
                                        @lang('Training')
                                    </x-nav.link>
                                    <x-nav.link :href="route('incentives')" class="ml-8"
                                                :active="is_active('incentives')">
                                        @lang('Incentives')
                                    </x-nav.link>
                                    @if(user()->role != 'Setter' && user()->role != 'Sales Rep')
                                        <x-nav.link :href="route('number-tracking.index')" class="ml-8"
                                                    :active="is_active('number-tracking.*')">
                                            @lang('Number Tracker')
                                        </x-nav.link>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if(user()->isMaster())
                            <x-nav.castle-icon/>
                        @endif

                        <button
                            class="p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
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
                                    <div>
                                        <span class="block px-4 pt-2 text-sm text-gray-600">
                                            {{ user()->first_name }}
                                        </span>
                                        <span class="block px-4 pb-2 pt-0.5 text-xs text-gray-500">
                                            {{ user()->email }}
                                        </span>
                                    </div>
                                    <hr class="my-2">
                                    
                                    <a href="{{ route('profile.show') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        @lang('Your Profile')
                                    </a>

                                    <button type="submit" form="form-sign-out"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">
                                        @lang('Logout')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div :class="{'block': open, 'hidden': !open}" class="border-b border-gray-700 md:hidden">
                    <div class="px-2 py-3 sm:px-3">
                        <div class="pt-4 pb-3 border-t border-gray-700">
                            <div class="flex items-center px-5">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full"
                                        src="{{ user()->photo_url }}"
                                        alt=""/>
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium leading-none text-white">
                                        {{ user()->first_name }}
                                    </div>
                                    <div class="mt-1 text-sm font-medium leading-none text-gray-400">
                                        {{ user()->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 px-2" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                                <a href="{{ route('profile.show') }}"
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
                </div>
            </div>
        </div>
        
        <div class="fixed bottom-0 block bg-white w-full md:hidden">
            <div class="flex items-center justify-between px-4 py-3 sm:px-3">
                @if(is_active('castle.*'))
                    <x-nav.link-mobile :href="route('home')" class="flex self-center">
                        <x-svg.chevron-left class="w-6 -ml-2"/> <span class="text-xs">@lang('Leave Admin')</span>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('castle.dashboard')" class="mt-1"
                            :active="is_active('castle.dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="dashboard" viewBox="0 0 24 24">
                            <path d="M20.021 12.593c-.141-.427-.314-.844-.516-1.242l-2.454 1.106c.217.394.39.81.517 1.242l2.453-1.106zm-12.573-.903c.271-.354.58-.675.919-.957l-1.89-1.969c-.328.294-.637.615-.918.957l1.889 1.969zm1.715-1.515c.379-.221.781-.396 1.198-.523l-1.034-2.569c-.41.142-.812.318-1.198.524l1.034 2.568zm-2.759 3.616c.121-.435.288-.854.498-1.25l-2.469-1.066c-.197.403-.364.822-.498 1.25l2.469 1.066zm9.434-6.2c-.387-.205-.79-.379-1.2-.519l-1.024 2.573c.417.125.82.299 1.2.519l1.024-2.573zm2.601 2.13c-.282-.342-.59-.663-.918-.957l-1.89 1.969c.339.282.647.604.918.957l1.89-1.969zm-5.791-3.059c-.219-.018-.437-.026-.649-.026s-.431.009-.65.026v2.784c.216-.025.434-.038.65-.038.216 0 .434.012.649.038v-2.784zm-.648 14.338c-1.294 0-2.343-1.049-2.343-2.343 0-.883.489-1.652 1.21-2.051l1.133-5.606 1.133 5.605c.722.399 1.21 1.168 1.21 2.051 0 1.295-1.049 2.344-2.343 2.344zm12-6c0 2.184-.586 4.233-1.61 5.999l-1.736-1.003c.851-1.471 1.346-3.174 1.346-4.996 0-5.523-4.477-10-10-10s-10 4.477-10 10c0 1.822.495 3.525 1.346 4.996l-1.736 1.003c-1.024-1.766-1.61-3.815-1.61-5.999 0-6.617 5.383-12 12-12s12 5.383 12 12z"/>
                            </symbol>
                            <use xlink:href="#dashboard" width="24" height="24" />
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('castle.permission.index')" class="mt-1"
                            :active="is_active('castle.permission.*')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="lock" viewBox="0 0 24 24">
                            <path d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM8.9 6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2H8.9V6zM18 20H6V10h12v10z"/>
                            </symbol>
                            <use xlink:href="#lock" width="24" height="24" />
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('castle.incentives')" class="mt-1"
                            :active="is_active('castle.incentives')">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <symbol id="star" viewBox="0 0 24 24">
                            <path d="M12 5.173l2.335 4.817 5.305.732-3.861 3.71.942 5.27-4.721-2.524-4.721 2.525.942-5.27-3.861-3.71 5.305-.733 2.335-4.817zm0-4.586l-3.668 7.568-8.332 1.151 6.064 5.828-1.48 8.279 7.416-3.967 7.416 3.966-1.48-8.279 6.064-5.827-8.332-1.15-3.668-7.569z"/>
                            </symbol>
                            <use xlink:href="#star" width="24" height="24" />
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('castle.masters.index')" class="mt-1"
                            :active="is_active('castle.masters.*')">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <symbol id="crown" viewBox="0 0 24 24">
                            <path d="M24 5c-3.923 3.265-5.623 4.716-7.15 4.716-2.44 0-3.681-3.675-4.85-7.716-1.165 4.028-2.41 7.715-4.853 7.715-1.513 0-3.168-1.404-7.147-4.715 3.321 7.018 3 14.292 3 17h18c0-1.718-.478-9.65 3-17zm-16.853 6.715c2.295 0 3.787-1.64 4.853-3.761 1.064 2.122 2.556 3.762 4.85 3.762 1.121 0 2.188-.43 3.126-1.042-.432 1.907-.68 3.72-.82 5.326h-14.322c-.138-1.617-.38-3.423-.808-5.324.927.607 2 1.039 3.121 1.039zm-2.14 8.285c-.003-.593-.016-1.267-.047-2h14.065c-.032.745-.046 1.422-.047 2h-13.971z"/>
                            </symbol>
                            <use xlink:href="#crown" width="24" height="24" />
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('castle.users.index')" class="mt-1"
                            :active="is_active('castle.users.*')">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <symbol id="group" viewBox="0 0 24 24">
                            <path d="M10.119 16.064c2.293-.53 4.427-.994 3.394-2.946-3.147-5.941-.835-9.118 2.488-9.118 3.388 0 5.643 3.299 2.488 9.119-1.065 1.964 1.149 2.427 3.393 2.946 1.985.458 2.118 1.428 2.118 3.107l-.003.828h-1.329c0-2.089.083-2.367-1.226-2.669-1.901-.438-3.695-.852-4.351-2.304-.239-.53-.395-1.402.226-2.543 1.372-2.532 1.719-4.726.949-6.017-.902-1.517-3.617-1.509-4.512-.022-.768 1.273-.426 3.479.936 6.05.607 1.146.447 2.016.206 2.543-.66 1.445-2.472 1.863-4.39 2.305-1.252.29-1.172.588-1.172 2.657h-1.331c0-2.196-.176-3.406 2.116-3.936zm-10.117 3.936h1.329c0-1.918-.186-1.385 1.824-1.973 1.014-.295 1.91-.723 2.316-1.612.212-.463.355-1.22-.162-2.197-.952-1.798-1.219-3.374-.712-4.215.547-.909 2.27-.908 2.819.015.935 1.567-.793 3.982-1.02 4.982h1.396c.44-1 1.206-2.208 1.206-3.9 0-2.01-1.312-3.1-2.998-3.1-2.493 0-4.227 2.383-1.866 6.839.774 1.464-.826 1.812-2.545 2.209-1.49.345-1.589 1.072-1.589 2.334l.002.618z"/>
                            </symbol>
                            <use xlink:href="#group" width="24" height="24" />
                        </svg>
                    </x-nav.link-mobile>
                @else
                    <x-nav.link-mobile :href="route('home')" class="mt-1"
                                :active="is_active('home')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="dashboard" viewBox="0 0 24 24">
                            <path d="M20.021 12.593c-.141-.427-.314-.844-.516-1.242l-2.454 1.106c.217.394.39.81.517 1.242l2.453-1.106zm-12.573-.903c.271-.354.58-.675.919-.957l-1.89-1.969c-.328.294-.637.615-.918.957l1.889 1.969zm1.715-1.515c.379-.221.781-.396 1.198-.523l-1.034-2.569c-.41.142-.812.318-1.198.524l1.034 2.568zm-2.759 3.616c.121-.435.288-.854.498-1.25l-2.469-1.066c-.197.403-.364.822-.498 1.25l2.469 1.066zm9.434-6.2c-.387-.205-.79-.379-1.2-.519l-1.024 2.573c.417.125.82.299 1.2.519l1.024-2.573zm2.601 2.13c-.282-.342-.59-.663-.918-.957l-1.89 1.969c.339.282.647.604.918.957l1.89-1.969zm-5.791-3.059c-.219-.018-.437-.026-.649-.026s-.431.009-.65.026v2.784c.216-.025.434-.038.65-.038.216 0 .434.012.649.038v-2.784zm-.648 14.338c-1.294 0-2.343-1.049-2.343-2.343 0-.883.489-1.652 1.21-2.051l1.133-5.606 1.133 5.605c.722.399 1.21 1.168 1.21 2.051 0 1.295-1.049 2.344-2.343 2.344zm12-6c0 2.184-.586 4.233-1.61 5.999l-1.736-1.003c.851-1.471 1.346-3.174 1.346-4.996 0-5.523-4.477-10-10-10s-10 4.477-10 10c0 1.822.495 3.525 1.346 4.996l-1.736 1.003c-1.024-1.766-1.61-3.815-1.61-5.999 0-6.617 5.383-12 12-12s12 5.383 12 12z"/>
                            </symbol>
                            <use xlink:href="#dashboard" width="24" height="24" />
                        </svg>
                    </x-nav.link-mobile>
                    <x-nav.link-mobile :href="route('scoreboard')" class="mt-1"
                                :active="is_active('scoreboard')">
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <symbol id="file" viewBox="0 0 24 24">
                                <path d="M11.362 2c4.156 0 2.638 6 2.638 6s6-1.65 6 2.457v11.543h-16v-20h7.362zm.827-2h-10.189v24h20v-14.386c0-2.391-6.648-9.614-9.811-9.614zm4.811 13h-10v-1h10v1zm0 2h-10v1h10v-1zm0 3h-10v1h10v-1z"/>
                                </symbol>
                                <use xlink:href="#file" width="24" height="24" />
                            </svg>
                    </x-nav.link-mobile>
                    <x-nav.link-mobile :href="route('trainings.index')" class="mt-1"
                                :active="is_active('trainings.*')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <symbol id="video" viewBox="0 0 24 24">
                                    <path d="M9 16.985v-10.021l9 5.157-9 4.864zm4-14.98c5.046.504 9 4.782 9 9.97 0 1.467-.324 2.856-.892 4.113l1.738 1.006c.732-1.555 1.154-3.285 1.154-5.119 0-6.303-4.842-11.464-11-11.975v2.005zm-10.109 14.082c-.568-1.257-.891-2.646-.891-4.112 0-5.188 3.954-9.466 9-9.97v-2.005c-6.158.511-11 5.672-11 11.975 0 1.833.421 3.563 1.153 5.118l1.738-1.006zm17.213 1.734c-1.817 2.523-4.769 4.175-8.104 4.175s-6.288-1.651-8.105-4.176l-1.746 1.011c2.167 3.122 5.768 5.169 9.851 5.169 4.082 0 7.683-2.047 9.851-5.168l-1.747-1.011z"/>
                                    </symbol>
                                    <use xlink:href="#video" width="24" height="24" />
                                </svg>
                    </x-nav.link-mobile>
                    <x-nav.link-mobile :href="route('incentives')" class="mt-1"
                                :active="is_active('incentives')">
                                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <symbol id="star" viewBox="0 0 24 24">
                                    <path d="M12 5.173l2.335 4.817 5.305.732-3.861 3.71.942 5.27-4.721-2.524-4.721 2.525.942-5.27-3.861-3.71 5.305-.733 2.335-4.817zm0-4.586l-3.668 7.568-8.332 1.151 6.064 5.828-1.48 8.279 7.416-3.967 7.416 3.966-1.48-8.279 6.064-5.827-8.332-1.15-3.668-7.569z"/>
                                    </symbol>
                                    <use xlink:href="#star" width="24" height="24" />
                                </svg>
                    </x-nav.link-mobile>

                    @if(user()->role != 'Setter' && user()->role != 'Sales Rep')
                        <x-nav.link-mobile :href="route('number-tracking.index')" class="mt-1"
                                    :active="is_active('number-tracking.*')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <symbol id="tracking" viewBox="0 0 24 24">
                                        <path d="M20.585 3.417l-5.194 13.873-1.743-6.939-6.932-1.733 13.869-5.201zm3.415-3.417l-24 9 12 3 3.014 12 8.986-24z"/>
                                        </symbol>
                                        <use xlink:href="#tracking" width="24" height="24"/>
                                    </svg>
                        </x-nav.link-mobile>
                    @endif

                    <x-nav.link-mobile :href="route('profile.index')" class="mt-1"
                                :active="is_active('profile.*')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <symbol id="user" viewBox="0 0 24 24">
                                    <path d="M12 2c3.032 0 5.5 2.467 5.5 5.5 0 1.458-.483 3.196-3.248 5.59 4.111 1.961 6.602 5.253 7.482 8.909h-19.486c.955-4.188 4.005-7.399 7.519-8.889-1.601-1.287-3.267-3.323-3.267-5.61 0-3.033 2.468-5.5 5.5-5.5zm0-2c-4.142 0-7.5 3.357-7.5 7.5 0 2.012.797 3.834 2.086 5.182-5.03 3.009-6.586 8.501-6.586 11.318h24c0-2.791-1.657-8.28-6.59-11.314 1.292-1.348 2.09-3.172 2.09-5.186 0-4.143-3.358-7.5-7.5-7.5z"/>
                                    </symbol>
                                    <use xlink:href="#user" width="24" height="24" />
                                </svg>
                    </x-nav.link-mobile>

                    @if(user()->isMaster())
                        <x-nav.castle-icon/>
                    @endif
                @endif
            </div>
        </div>
        <x-form id="form-sign-out" :route="route('logout')" class="hidden"></x-form>
    </nav>
</div>