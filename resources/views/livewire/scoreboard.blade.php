<div>
    <div x-data="{openModal: false,
                    openDoorsTab: 'daily',
                    openHoursTab: 'daily',
                    openSetsTab: 'daily',
                    openSetClosesTab: 'daily',
                    openClosesTab: 'daily',
                    active: 'border-b-2 border-green-base text-green-base',
                    inactive: 'text-gray-900 hover:text-gray-800'}">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between">
                    <div class="flex justify-start">
                        <h3 class="text-lg text-gray-900">Scoring</h3>
                    </div>
                </div>

                <div class="mt-6">
                    <span
                        class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                        Top 10 Doors
                    </span>

                    <ul class="flex border-b mt-3">
                        <li @click="openDoorsTab = 'daily'" class="-mb-px mr-4">
                            <a :class="openDoorsTab === 'daily' ? active: inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10DoorsPeriod('daily')">Daily</a>
                        </li>
                        <li @click="openDoorsTab = 'weekly'" class="-mb-px mr-4">
                            <a :class="openDoorsTab === 'weekly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10DoorsPeriod('weekly')">Weekly</a>
                        </li>
                        <li @click="openDoorsTab = 'monthly'" class="-mb-px mr-4">
                            <a :class="openDoorsTab === 'monthly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10DoorsPeriod('monthly')">Monthly</a>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($top10Doors->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th by="rank">
                                                        @lang('Rank')
                                                    </x-table.th>
                                                    <x-table.th by="representative">
                                                        @lang('Representative')
                                                    </x-table.th>
                                                    <x-table.th by="doors">
                                                        @lang('Doors')
                                                    </x-table.th>
                                                    <x-table.th by="office">
                                                        @lang('Office')
                                                    </x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($top10Doors as $user)
                                                    <x-table.tr :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer">
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->doors }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="h-96">
                                            <div class="flex justify-center align-middle">
                                                <div class="text-sm text-center text-gray-700">
                                                    No data for this period.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <span
                        class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                        Top 10 Hours
                    </span>

                    <ul class="flex border-b mt-3">
                        <li @click="openHoursTab = 'daily'" class="-mb-px mr-4">
                            <a :class="openHoursTab === 'daily' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10HoursPeriod('daily')">Daily</a>
                        </li>
                        <li @click="openHoursTab = 'weekly'" class="-mb-px mr-4">
                            <a :class="openHoursTab === 'weekly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10HoursPeriod('weekly')">Weekly</a>
                        </li>
                        <li @click="openHoursTab = 'monthly'" class="-mb-px mr-4">
                            <a :class="openHoursTab === 'monthly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10HoursPeriod('monthly')">Monthly</a>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($top10Hours->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th by="rank">
                                                        @lang('Rank')
                                                    </x-table.th>
                                                    <x-table.th by="representative">
                                                        @lang('Representative')
                                                    </x-table.th>
                                                    <x-table.th by="hours">
                                                        @lang('Hours')
                                                    </x-table.th>
                                                    <x-table.th by="office">
                                                        @lang('Office')
                                                    </x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($top10Hours as $user)
                                                    <x-table.tr :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer">
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->first_name }} {{ $user->last_name }}
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->hours }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="h-96">
                                            <div class="flex justify-center align-middle">
                                                <div class="text-sm text-center text-gray-700">
                                                    No data for this period.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <span
                        class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                        Top 10 Sets
                    </span>

                    <ul class="flex border-b mt-3">
                        <li @click="openSetsTab = 'daily'" class="-mb-px mr-4">
                            <a :class="openSetsTab === 'daily' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10SetsPeriod('daily')">Daily</a>
                        </li>
                        <li @click="openSetsTab = 'weekly'" class="-mb-px mr-4">
                            <a :class="openSetsTab === 'weekly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10SetsPeriod('weekly')">Weekly</a>
                        </li>
                        <li @click="openSetsTab = 'monthly'" class="-mb-px mr-4">
                            <a :class="openSetsTab === 'monthly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10SetsPeriod('monthly')">Monthly</a>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($top10Sets->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th by="rank">
                                                        @lang('Rank')
                                                    </x-table.th>
                                                    <x-table.th by="representative">
                                                        @lang('Representative')
                                                    </x-table.th>
                                                    <x-table.th by="sets">
                                                        @lang('Sets')
                                                    </x-table.th>
                                                    <x-table.th by="office">
                                                        @lang('Office')
                                                    </x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($top10Sets as $user)
                                                    <x-table.tr :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer">
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->first_name }} {{ $user->last_name }}
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->sets }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="h-96">
                                            <div class="flex justify-center align-middle">
                                                <div class="text-sm text-center text-gray-700">
                                                    No data for this period.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <span
                        class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                        Top 10 Set Closes
                    </span>

                    <ul class="flex border-b mt-3">
                        <li @click="openSetClosesTab = 'daily'" class="-mb-px mr-4">
                            <a :class="openSetClosesTab === 'daily' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10SetClosesPeriod('daily')">Daily</a>
                        </li>
                        <li @click="openSetClosesTab = 'weekly'" class="-mb-px mr-4">
                            <a :class="openSetClosesTab === 'weekly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10SetClosesPeriod('weekly')">Weekly</a>
                        </li>
                        <li @click="openSetClosesTab = 'monthly'" class="-mb-px mr-4">
                            <a :class="openSetClosesTab === 'monthly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10SetClosesPeriod('monthly')">Monthly</a>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($top10SetCloses->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th by="rank">
                                                        @lang('Rank')
                                                    </x-table.th>
                                                    <x-table.th by="representative">
                                                        @lang('Representative')
                                                    </x-table.th>
                                                    <x-table.th by="set_closes">
                                                        @lang('Set Closes')
                                                    </x-table.th>
                                                    <x-table.th by="office">
                                                        @lang('Office')
                                                    </x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($top10SetCloses as $user)
                                                    <x-table.tr :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer">
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->first_name }} {{ $user->last_name }}
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->set_closes }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="h-96">
                                            <div class="flex justify-center align-middle">
                                                <div class="text-sm text-center text-gray-700">
                                                    No data for this period.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <span
                        class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                        Top 10 Closes
                    </span>

                    <ul class="flex border-b mt-3">
                        <li @click="openClosesTab = 'daily'" class="-mb-px mr-4">
                            <a :class="openClosesTab === 'daily' ? active: inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10ClosesPeriod('daily')">Daily</a>
                        </li>
                        <li @click="openClosesTab = 'weekly'" class="-mb-px mr-4">
                            <a :class="openClosesTab === 'weekly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10ClosesPeriod('weekly')">Weekly</a>
                        </li>
                        <li @click="openClosesTab = 'monthly'" class="-mb-px mr-4">
                            <a :class="openClosesTab === 'monthly' ? active : inactive"
                                class="bg-white inline-block py-2 text-sm font-semibold cursor-pointer"
                                wire:click.prevent="setTop10ClosesPeriod('monthly')">Monthly</a>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($top10Closes->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th by="rank">
                                                        @lang('Rank')
                                                    </x-table.th>
                                                    <x-table.th by="representative">
                                                        @lang('Representative')
                                                    </x-table.th>
                                                    <x-table.th by="closes">
                                                        @lang('Closes')
                                                    </x-table.th>
                                                    <x-table.th by="office">
                                                        @lang('Office')
                                                    </x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($top10Closes as $user)
                                                    <x-table.tr :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer">
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->first_name }} {{ $user->last_name }}
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->closes }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="h-96">
                                            <div class="flex justify-center align-middle">
                                                <div class="text-sm text-center text-gray-700">
                                                    No data for this period.
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-svg.spinner color="#9fa6b2" class="fixed hidden left-1/2 top-1/2 w-20"
                wire:loading.class.remove="hidden">
            </x-svg.spinner>

            @if ($userId)
                <div x-cloak x-show="openModal" wire:loading.remove
                    class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                    <div x-show="openModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <div class="relative bottom-24" x-show="openModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="absolute top-0 right-0 pt-4 pr-4">
                            <button type="button" x-on:click="openModal = false; setTimeout(() => open = true, 1000)"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
                                aria-label="Close">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="px-4 py-5 sm:p-6 bg-gray-50">
                            <div class="flex justify-between">
                                <div class="flex justify-start">
                                    <div class="flex items-center">
                                        <div>
                                            <img class="inline-block h-16 w-16 rounded-full"
                                                src="{{ $userArray['photo_url'] }}" alt="" />
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm leading-5 font-medium text-gray-700 group-hover:text-gray-900">
                                                {{ $userArray['first_name'] }} {{ $userArray['last_name'] }}
                                            </p>
                                            <p
                                                class="text-xs leading-4 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150">
                                                {{ $userArray['office_name'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div class="flex justify-between grid grid-cols-4 row-gap-1 col-gap-2 m-1 p-2">
                                    <div class="col-span-2 text-xs text-gray-900">
                                        <div
                                            class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                                            <div class="col-span-2 text-xs text-gray-900">
                                                DPS RATIO
                                            </div>
                                            <div class="col-span-2 text-xl font-bold text-gray-900">
                                                {{ number_format($dpsRatio) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-xs text-gray-900">
                                        <div
                                            class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                                            <div class="col-span-2 text-xs text-gray-900">
                                                HPS RATIO
                                            </div>
                                            <div class="col-span-2 text-xl font-bold text-gray-900">
                                                {{ number_format($hpsRatio) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-xs text-gray-900">
                                        <div
                                            class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                                            <div class="col-span-2 text-xs text-gray-900">
                                                SIT RATIO
                                            </div>
                                            <div class="col-span-2 text-xl font-bold text-gray-900">
                                                {{ number_format($sitRatio) }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-xs text-gray-900">
                                        <div
                                            class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                                            <div class="col-span-2 text-xs text-gray-900">
                                                CLOSE RATIO
                                            </div>
                                            <div class="col-span-2 text-xl font-bold text-gray-900">
                                                {{ number_format($closeRatio) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bar Chart -->
                                <div class="flex border-gray-200 border-2 m-1 h-48 rounded-lg">
                                    <div class="w-2/3" id="chartdiv"></div>
                                    <div class="w-1/3 block pt-4 space-y-1">
                                        <div id="hours">0 hours</div>
                                        <div id="doors">0 doors</div>
                                        <div id="sits">0 sits</div>
                                        <div id="sets">0 sets</div>
                                        <div id="set_closes">0 set closes</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    am4core.ready(function() {
        // Themes begin
        window.addEventListener('setUserNumbers', event => {
            am4core.useTheme(am4themes_animated);
            // Themes end
            if (event.detail.doors && event.detail.hours && event.detail.sits && event.detail
                .sets && event.detail.set_closes) {
                var chart = am4core.create("chartdiv", am4charts.SlicedChart);
                chart.data = [{
                    "name": "doors",
                    "value2": event.detail.doors
                }, {
                    "name": "hours",
                    "value2": event.detail.hours
                }, {
                    "name": "sits",
                    "value2": event.detail.sits
                }, {
                    "name": "sets",
                    "value2": event.detail.sets
                }, {
                    "name": "Set Closes",
                    "value2": event.detail.set_closes
                }];
                chart.logo.disabled = true;
                var series1 = chart.series.push(new am4charts.FunnelSeries());
                series1.dataFields.value = "value2";
                series1.dataFields.category = "name";
                series1.labels.template.disabled = true;
                document.getElementById("hours").innerHTML = event.detail.doors + " " + "Hours";
                document.getElementById("hours").style.color = "#67B7DC";
                document.getElementById("doors").innerHTML = event.detail.hours + " " + "Doors";
                document.getElementById("doors").style.color = "#648FD5";
                document.getElementById("sits").innerHTML = event.detail.sits + " " + "Sits";
                document.getElementById("sits").style.color = "#6670DB";
                document.getElementById("sets").innerHTML = event.detail.sets + " " + "Sets";
                document.getElementById("sets").style.color = "#8067DC";
                document.getElementById("set_closes").innerHTML = event.detail.set_closes + " " +
                    "Set Closes";
                document.getElementById("set_closes").style.color = "#A367DC";
            } else {
                document.getElementById("chartdiv").innerHTML = "No data to display";
            }
        })
    }); // end am4core.ready()

</script>
