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

                                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                                        <x-nav.link :href="route('castle.rates.index')" class="ml-4"
                                                    :active="is_active('castle.rates.index')">
                                            @lang('Manage Compensations')
                                        </x-nav.link>
                                    @endif

                                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                                        <x-nav.link
                                            :href="route('castle.manage-trainings.index', ['department' => user()->department_id] )"
                                            class="ml-4"
                                            :active="is_active('castle.manage-trainings.index')">
                                            @lang('Manage Trainings')
                                        </x-nav.link>
                                    @endif

                                    @if(user()->role == 'Admin' || user()->role == 'Owner' )
                                        <x-nav.link :href="route('castle.departments.index')" class="ml-4"
                                                    :active="is_active('castle.departments.*')">
                                            @lang('Departments')
                                        </x-nav.link>
                                    @endif

                                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                                        <x-nav.link :href="route('castle.incentives.index')" class="ml-4"
                                                    :active="is_active('castle.incentives.*')">
                                            @lang('Incentives')
                                        </x-nav.link>
                                    @endif

                                    @if(user()->role != 'Office Manager')
                                        <x-nav.link :href="route('castle.regions.index')" class="ml-4"
                                                    :active="is_active('castle.regions.*')">
                                            @lang('Regions')
                                        </x-nav.link>
                                    @endif

                                    <x-nav.link :href="route('castle.offices.index')" class="ml-4"
                                                :active="is_active('castle.offices.*')">
                                        @lang('Offices')
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
                                    <x-nav.link
                                        :href="route('trainings.index', ['department' => user()->department_id])"
                                        class="ml-8"
                                        :active="is_active('trainings.*')">
                                        @lang('Training')
                                    </x-nav.link>
                                    <x-nav.link :href="route('incentives.index')" class="ml-8"
                                                :active="is_active('incentives')">
                                        @lang('Incentives')
                                    </x-nav.link>
                                    <x-nav.link :href="route('reports.index')" class="ml-8"
                                                :active="is_active('reports.*')">
                                        @lang('Reports')
                                    </x-nav.link>
                                    <x-nav.link :href="route('number-tracking.index')" class="ml-8"
                                                :active="is_active('number-tracking.*')">
                                        @lang('Number Tracker')
                                    </x-nav.link>

                                @endif
                            </div>
                        </div>

                        @if(user()->userLevel() != 'Sales Rep' && user()->userLevel() != 'Setter')
                            <x-nav.castle-icon/>
                    @endif
                    <!--
                        <button
                            class="p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            aria-label="Notifications">
                            <x-svg.notification/>
                        </button> -->

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
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 rounded-md shadow-lg z-10">
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

        <div class="z-40 fixed bottom-0 block bg-white w-full md:hidden overflow-x-scroll">
            <div class="flex items-center justify-between px-4 py-3 sm:px-3">
                @if(is_active('castle.*'))
                    <x-nav.link-mobile :href="route('home')" class="flex flex-row items-center whitespace-no-wrap">
                        <x-svg.chevron-left class="w-6 -ml-2"/>
                        <span class="text-xs">@lang('Leave Admin')</span>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('castle.dashboard')" class="mt-1"
                                       :active="is_active('castle.dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="dashboard" viewBox="0 0 24 24">
                                <path
                                    d="M20.021 12.593c-.141-.427-.314-.844-.516-1.242l-2.454 1.106c.217.394.39.81.517 1.242l2.453-1.106zm-12.573-.903c.271-.354.58-.675.919-.957l-1.89-1.969c-.328.294-.637.615-.918.957l1.889 1.969zm1.715-1.515c.379-.221.781-.396 1.198-.523l-1.034-2.569c-.41.142-.812.318-1.198.524l1.034 2.568zm-2.759 3.616c.121-.435.288-.854.498-1.25l-2.469-1.066c-.197.403-.364.822-.498 1.25l2.469 1.066zm9.434-6.2c-.387-.205-.79-.379-1.2-.519l-1.024 2.573c.417.125.82.299 1.2.519l1.024-2.573zm2.601 2.13c-.282-.342-.59-.663-.918-.957l-1.89 1.969c.339.282.647.604.918.957l1.89-1.969zm-5.791-3.059c-.219-.018-.437-.026-.649-.026s-.431.009-.65.026v2.784c.216-.025.434-.038.65-.038.216 0 .434.012.649.038v-2.784zm-.648 14.338c-1.294 0-2.343-1.049-2.343-2.343 0-.883.489-1.652 1.21-2.051l1.133-5.606 1.133 5.605c.722.399 1.21 1.168 1.21 2.051 0 1.295-1.049 2.344-2.343 2.344zm12-6c0 2.184-.586 4.233-1.61 5.999l-1.736-1.003c.851-1.471 1.346-3.174 1.346-4.996 0-5.523-4.477-10-10-10s-10 4.477-10 10c0 1.822.495 3.525 1.346 4.996l-1.736 1.003c-1.024-1.766-1.61-3.815-1.61-5.999 0-6.617 5.383-12 12-12s12 5.383 12 12z"/>
                            </symbol>
                            <use xlink:href="#dashboard" width="24" height="24"/>
                        </svg>
                    </x-nav.link-mobile>

                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <x-nav.link-mobile :href="route('castle.rates.index')" class="mt-1"
                                           :active="is_active('castle.rates.index')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path
                                    d="M18 11c2.757 0 5 2.243 5 5s-2.243 5-5 5-5-2.243-5-5 2.243-5 5-5zm0-1c-3.313 0-6 2.687-6 6s2.687 6 6 6 6-2.687 6-6-2.687-6-6-6zm.5 8.474v.526h-.5v-.499c-.518-.009-1.053-.132-1.5-.363l.228-.822c.478.186 1.114.383 1.612.27.574-.13.692-.721.057-1.005-.465-.217-1.889-.402-1.889-1.622 0-.681.52-1.292 1.492-1.425v-.534h.5v.509c.362.01.768.073 1.221.21l-.181.824c-.384-.135-.808-.257-1.222-.232-.744.043-.81.688-.29.958.856.402 1.972.7 1.972 1.773.001.858-.672 1.315-1.5 1.432zm-7.911-5.474h-2.589v-2h3.765c-.484.602-.881 1.274-1.176 2zm-.589 3h-2v-2h2.264c-.166.641-.264 1.309-.264 2zm2.727-6h-4.727v-2h7v.589c-.839.341-1.604.822-2.273 1.411zm2.273-6h-7v-2h7v2zm0 3h-7v-2h7v2zm-4.411 12h-2.589v-2h2.069c.088.698.264 1.369.52 2zm-10.589-11h7v2h-7v-2zm0 3h7v2h-7v-2zm12.727 11h-4.727v-2h3.082c.438.753.994 1.428 1.645 2zm-12.727-5h7v2h-7v-2zm0 3h7v2h-7v-2zm0-6h7v2h-7v-2z"/>
                            </svg>
                        </x-nav.link-mobile>
                    @endif

                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <x-nav.link-mobile
                            :href="route('castle.manage-trainings.index', ['department' => user()->department_id])"
                            class="mt-1"
                            :active="is_active('castle.manage-trainings.index')">
                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                 width="24" height="24" viewBox="0 0 24.000000 24.000000"
                                 preserveAspectRatio="xMidYMid meet">

                                <g transform="translate(0.000000,24.000000) scale(0.100000,-0.100000)" stroke="none">
                                    <path d="M117 193 c-5 -29 -20 -52 -62 -93 -30 -30 -55 -60 -55 -67 0 -32 27
                                    -20 85 37 64 63 64 63 70 37 9 -34 20 -34 56 2 35 36 36 47 4 55 -15 4 -22 11
                                    -19 19 7 18 -22 42 -31 27 -5 -8 -12 -6 -25 5 -16 15 -18 13 -23 -22z m73 -44
                                    c0 -19 -24 -20 -42 -1 -28 27 -15 47 18 26 13 -9 24 -20 24 -25z m-110 -64
                                    c-30 -30 -56 -52 -59 -50 -3 3 19 30 49 60 30 30 56 52 59 50 3 -3 -19 -30
                                    -49 -60z m125 35 c-10 -11 -23 -20 -28 -20 -6 0 0 11 13 25 27 29 42 25 15 -5z"/>
                                </g>
                            </svg>
                        </x-nav.link-mobile>
                    @endif

                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <x-nav.link-mobile :href="route('castle.incentives.index')" class="mt-1"
                                           :active="is_active('castle.incentives.*')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path
                                    d="M12 5.173l2.335 4.817 5.305.732-3.861 3.71.942 5.27-4.721-2.524-4.721 2.525.942-5.27-3.861-3.71 5.305-.733 2.335-4.817zm0-4.586l-3.668 7.568-8.332 1.151 6.064 5.828-1.48 8.279 7.416-3.967 7.416 3.966-1.48-8.279 6.064-5.827-8.332-1.15-3.668-7.569z"/>
                            </svg>
                        </x-nav.link-mobile>
                    @endif

                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <x-nav.link-mobile :href="route('castle.departments.index')" class="mt-1"
                                           :active="is_active('castle.departments.*')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path
                                    d="M18 10.031v-6.423l-6.036-3.608-5.964 3.569v6.499l-6 3.224v7.216l6.136 3.492 5.864-3.393 5.864 3.393 6.136-3.492v-7.177l-6-3.3zm-1.143.036l-4.321 2.384v-4.956l4.321-2.539v5.111zm-4.895-8.71l4.272 2.596-4.268 2.509-4.176-2.554 4.172-2.551zm-10.172 12.274l4.778-2.53 4.237 2.417-4.668 2.667-4.347-2.554zm4.917 3.587l4.722-2.697v5.056l-4.722 2.757v-5.116zm6.512-3.746l4.247-2.39 4.769 2.594-4.367 2.509-4.649-2.713zm9.638 6.323l-4.421 2.539v-5.116l4.421-2.538v5.115z"/>
                            </svg>
                        </x-nav.link-mobile>
                    @endif

                    @if(user()->role != 'Office Manager')
                        <x-nav.link-mobile :href="route('castle.regions.index')" class="mt-1"
                                           :active="is_active('castle.regions.*')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <symbol id="region" viewBox="0 0 24 24">
                                    <path
                                        d="M18 0c-3.148 0-6 2.553-6 5.702 0 4.682 4.783 5.177 6 12.298 1.217-7.121 6-7.616 6-12.298 0-3.149-2.852-5.702-6-5.702zm0 8c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2zm-12-3c-2.099 0-4 1.702-4 3.801 0 3.121 3.188 3.451 4 8.199.812-4.748 4-5.078 4-8.199 0-2.099-1.901-3.801-4-3.801zm0 5.333c-.737 0-1.333-.597-1.333-1.333s.596-1.333 1.333-1.333 1.333.596 1.333 1.333-.596 1.333-1.333 1.333zm6 5.775l-3.215-1.078c.365-.634.777-1.128 1.246-1.687l1.969.657 1.92-.64c.388.521.754 1.093 1.081 1.75l-3.001.998zm12 7.892l-6.707-2.427-5.293 2.427-5.581-2.427-6.419 2.427 3.62-8.144c.299.76.554 1.776.596 3.583l-.443.996 2.699-1.021 4.809 2.091.751-3.725.718 3.675 4.454-2.042 3.099 1.121-.461-1.055c.026-.392.068-.78.131-1.144.144-.84.345-1.564.585-2.212l3.442 7.877z"/>
                                </symbol>
                                <use xlink:href="#region" width="24" height="24"/>
                            </svg>
                        </x-nav.link-mobile>
                    @endif

                    <x-nav.link-mobile :href="route('castle.offices.index')" class="mt-1"
                                       :active="is_active('castle.offices.*')">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <symbol id="office" viewBox="0 0 24 24">
                                <path
                                    d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
                            </symbol>
                            <use xlink:href="#office" width="24" height="24"/>
                        </svg>
                    </x-nav.link-mobile>
                    <x-nav.link-mobile :href="route('castle.users.index')" class="mt-1"
                                       :active="is_active('castle.users.*')">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <symbol id="group" viewBox="0 0 24 24">
                                <path
                                    d="M10.119 16.064c2.293-.53 4.427-.994 3.394-2.946-3.147-5.941-.835-9.118 2.488-9.118 3.388 0 5.643 3.299 2.488 9.119-1.065 1.964 1.149 2.427 3.393 2.946 1.985.458 2.118 1.428 2.118 3.107l-.003.828h-1.329c0-2.089.083-2.367-1.226-2.669-1.901-.438-3.695-.852-4.351-2.304-.239-.53-.395-1.402.226-2.543 1.372-2.532 1.719-4.726.949-6.017-.902-1.517-3.617-1.509-4.512-.022-.768 1.273-.426 3.479.936 6.05.607 1.146.447 2.016.206 2.543-.66 1.445-2.472 1.863-4.39 2.305-1.252.29-1.172.588-1.172 2.657h-1.331c0-2.196-.176-3.406 2.116-3.936zm-10.117 3.936h1.329c0-1.918-.186-1.385 1.824-1.973 1.014-.295 1.91-.723 2.316-1.612.212-.463.355-1.22-.162-2.197-.952-1.798-1.219-3.374-.712-4.215.547-.909 2.27-.908 2.819.015.935 1.567-.793 3.982-1.02 4.982h1.396c.44-1 1.206-2.208 1.206-3.9 0-2.01-1.312-3.1-2.998-3.1-2.493 0-4.227 2.383-1.866 6.839.774 1.464-.826 1.812-2.545 2.209-1.49.345-1.589 1.072-1.589 2.334l.002.618z"/>
                            </symbol>
                            <use xlink:href="#group" width="24" height="24"/>
                        </svg>
                    </x-nav.link-mobile>
                @else
                    <x-nav.link-mobile :href="route('home')" class="mt-1"
                                       :active="is_active('home')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="dashboard" viewBox="0 0 24 24">
                                <path
                                    d="M20.021 12.593c-.141-.427-.314-.844-.516-1.242l-2.454 1.106c.217.394.39.81.517 1.242l2.453-1.106zm-12.573-.903c.271-.354.58-.675.919-.957l-1.89-1.969c-.328.294-.637.615-.918.957l1.889 1.969zm1.715-1.515c.379-.221.781-.396 1.198-.523l-1.034-2.569c-.41.142-.812.318-1.198.524l1.034 2.568zm-2.759 3.616c.121-.435.288-.854.498-1.25l-2.469-1.066c-.197.403-.364.822-.498 1.25l2.469 1.066zm9.434-6.2c-.387-.205-.79-.379-1.2-.519l-1.024 2.573c.417.125.82.299 1.2.519l1.024-2.573zm2.601 2.13c-.282-.342-.59-.663-.918-.957l-1.89 1.969c.339.282.647.604.918.957l1.89-1.969zm-5.791-3.059c-.219-.018-.437-.026-.649-.026s-.431.009-.65.026v2.784c.216-.025.434-.038.65-.038.216 0 .434.012.649.038v-2.784zm-.648 14.338c-1.294 0-2.343-1.049-2.343-2.343 0-.883.489-1.652 1.21-2.051l1.133-5.606 1.133 5.605c.722.399 1.21 1.168 1.21 2.051 0 1.295-1.049 2.344-2.343 2.344zm12-6c0 2.184-.586 4.233-1.61 5.999l-1.736-1.003c.851-1.471 1.346-3.174 1.346-4.996 0-5.523-4.477-10-10-10s-10 4.477-10 10c0 1.822.495 3.525 1.346 4.996l-1.736 1.003c-1.024-1.766-1.61-3.815-1.61-5.999 0-6.617 5.383-12 12-12s12 5.383 12 12z"/>
                            </symbol>
                            <use xlink:href="#dashboard" width="24" height="24"/>
                        </svg>
                    </x-nav.link-mobile>
                    <x-nav.link-mobile :href="route('scoreboard')" class="mt-1"
                                       :active="is_active('scoreboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path
                                d="M5.627 19.027l2.265 3.359c-.643.448-1.219.991-1.708 1.614l-.48-2.506h-2.704c.745-.949 1.631-1.782 2.627-2.467zm12.746 0l-2.265 3.359c.643.448 1.219.991 1.708 1.614l.48-2.506h2.704c-.745-.949-1.631-1.782-2.627-2.467zm-6.373-2.388c-2.198 0-4.256.595-6.023 1.632l2.271 3.368c1.118-.607 2.396-.948 3.752-.948s2.634.34 3.752.948l2.271-3.368c-1.767-1.037-3.825-1.632-6.023-1.632zm-2.341 3.275l-.537-.287-.536.287.107-.599-.438-.421.602-.083.265-.547.266.547.603.083-.438.421.106.599zm3.149-.115l-.818-.438-.82.438.164-.915-.671-.643.921-.127.406-.835.405.835.92.127-.671.643.164.915zm2.583.115l-.536-.287-.536.287.106-.599-.438-.421.603-.083.266-.547.265.547.603.083-.438.421.105.599zm2.618-10.258c-.286.638-.585 1.231-.882 1.783 4.065-1.348 6.501-5.334 6.873-9.439h-4.077c-.036.482-.08.955-.139 1.405h2.688c-.426 2.001-1.548 4.729-4.463 6.251zm-6.009 5.983c.577 0 1.152.039 1.721.115 1.221-3.468 5.279-6.995 5.279-15.754h-14c0 8.758 4.065 12.285 5.29 15.752.564-.075 1.136-.113 1.71-.113zm4.921-13.639c-.368 4.506-1.953 7.23-3.372 9.669-.577.993-1.136 1.953-1.543 2.95-.408-.998-.969-1.959-1.548-2.953-1.422-2.437-3.011-5.161-3.379-9.666h9.842zm-10.048 9.438c-.297-.552-.596-1.145-.882-1.783-2.915-1.521-4.037-4.25-4.464-6.251h2.688c-.058-.449-.102-.922-.138-1.404h-4.077c.372 4.105 2.808 8.091 6.873 9.438zm3.27-8.438h-1.383c.374 3.118 1.857 7.023 3.24 8.547-1.125-2.563-1.849-5.599-1.857-8.547z"/>
                        </svg>
                    </x-nav.link-mobile>
                    <x-nav.link-mobile :href="route('trainings.index', ['department' => user()->department_id])"
                                       class="mt-1"
                                       :active="is_active('trainings.*')">
                        <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" viewBox="0 0 24.000000 24.000000"
                             preserveAspectRatio="xMidYMid meet">

                            <g transform="translate(0.000000,24.000000) scale(0.100000,-0.100000)" stroke="none">
                                <path d="M117 193 c-5 -29 -20 -52 -62 -93 -30 -30 -55 -60 -55 -67 0 -32 27
                                    -20 85 37 64 63 64 63 70 37 9 -34 20 -34 56 2 35 36 36 47 4 55 -15 4 -22 11
                                    -19 19 7 18 -22 42 -31 27 -5 -8 -12 -6 -25 5 -16 15 -18 13 -23 -22z m73 -44
                                    c0 -19 -24 -20 -42 -1 -28 27 -15 47 18 26 13 -9 24 -20 24 -25z m-110 -64
                                    c-30 -30 -56 -52 -59 -50 -3 3 19 30 49 60 30 30 56 52 59 50 3 -3 -19 -30
                                    -49 -60z m125 35 c-10 -11 -23 -20 -28 -20 -6 0 0 11 13 25 27 29 42 25 15 -5z"/>
                            </g>
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('incentives.index')" class="mt-1"
                                       :active="is_active('incentives')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path
                                d="M12 5.173l2.335 4.817 5.305.732-3.861 3.71.942 5.27-4.721-2.524-4.721 2.525.942-5.27-3.861-3.71 5.305-.733 2.335-4.817zm0-4.586l-3.668 7.568-8.332 1.151 6.064 5.828-1.48 8.279 7.416-3.967 7.416 3.966-1.48-8.279 6.064-5.827-8.332-1.15-3.668-7.569z"/>
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('number-tracking.index')" class="mt-1"
                                       :active="is_active('number-tracking.*')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="tracking" viewBox="0 0 24 24">
                                <path
                                    d="M20.585 3.417l-5.194 13.873-1.743-6.939-6.932-1.733 13.869-5.201zm3.415-3.417l-24 9 12 3 3.014 12 8.986-24z"/>
                            </symbol>
                            <use xlink:href="#tracking" width="24" height="24"/>
                        </svg>
                    </x-nav.link-mobile>

                    <x-nav.link-mobile :href="route('profile.index')" class="mt-1"
                                       :active="is_active('profile.*')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="user" viewBox="0 0 24 24">
                                <path
                                    d="M12 2c3.032 0 5.5 2.467 5.5 5.5 0 1.458-.483 3.196-3.248 5.59 4.111 1.961 6.602 5.253 7.482 8.909h-19.486c.955-4.188 4.005-7.399 7.519-8.889-1.601-1.287-3.267-3.323-3.267-5.61 0-3.033 2.468-5.5 5.5-5.5zm0-2c-4.142 0-7.5 3.357-7.5 7.5 0 2.012.797 3.834 2.086 5.182-5.03 3.009-6.586 8.501-6.586 11.318h24c0-2.791-1.657-8.28-6.59-11.314 1.292-1.348 2.09-3.172 2.09-5.186 0-4.143-3.358-7.5-7.5-7.5z"/>
                            </symbol>
                            <use xlink:href="#user" width="24" height="24"/>
                        </svg>
                    </x-nav.link-mobile>

                    @if(user()->userLevel() != 'Sales Rep' && user()->userLevel() != 'Setter')
                        <x-nav.castle-icon/>
                    @endif
                @endif
            </div>
        </div>
        <x-form id="form-sign-out" :route="route('logout')" class="hidden"></x-form>
    </nav>
</div>
