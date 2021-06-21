<div>
    <section class="flex flex-wrap">
        <div class="sticky self-start left-0 top-0 w-72 md:w-96 sans-serif flex-none bg-white">
            <ul class="flex mt-3 border-b">
                <li class="mr-4 -mb-px">
                    <span class="
                        bg-white inline-block py-2 text-sm font-semibold cursor-pointer
                            @if($period == 'd')
                                border-b-2 border-green-base text-green-base
                            @else text-gray-900
                                hover:text-gray-800
                            @endIf
                        "
                          wire:click="setPeriod('d')">
                        Day
                    </span>
                </li>
                <li class="mr-4">
                    <span class="
                        bg-white inline-block py-2 text-sm font-semibold cursor-pointer
                            @if($period == 'w')
                                border-b-2 border-green-base text-green-base
                            @else text-gray-900
                                hover:text-gray-800
                            @endIf
                        "
                          wire:click="setPeriod('w')">
                        Week
                    </span>
                </li>
                <li class="mr-4">
                    <span class="
                        bg-white inline-block py-2 text-sm font-semibold cursor-pointer
                            @if($period == 'm')
                                border-b-2 border-green-base text-green-base
                            @else text-gray-900
                                hover:text-gray-800
                            @endIf
                        "
                          wire:click="setPeriod('m')">
                        Month
                    </span>
                <li>
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="relative hidden w-6 top-2"
                        wire:loading.class.remove="hidden" wire:target="setPeriod">
                    </x-svg.spinner>
                </li>
            </ul>
            <div x-data="app()" x-init="[initDate(), getNoOfDays()]">
                <div class="container mx-auto">
                    <div class="mt-6 mb-5">
                        <div class="relative">
                            <input type="hidden" name="date" x-ref="date">

                            <div class="top-0 left-0 p-4 bg-white border-2 border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span x-text="MONTH_NAMES[month]"
                                              class="text-lg font-bold text-gray-800"></span>
                                        <span x-text="year" class="ml-1 text-lg font-normal text-gray-600"></span>
                                    </div>
                                    <div>
                                        <button
                                            type="button"
                                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200"
                                            @click="
                                            month == 0 ? year-- : year = year;
                                            month > 0 ? month-- : month = 11;
                                            getNoOfDays()
                                        ">
                                            <svg class="inline-flex w-6 h-6 text-gray-500" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200"
                                            @click="
                                            month == 11 ? year++ : year = year;
                                            month < 11 ? month++ : month = 0;
                                            getNoOfDays()
                                        ">
                                            <svg class="inline-flex w-6 h-6 text-gray-500" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-wrap mb-3 -mx-1">
                                    <template x-for="(day, index) in DAYS" :key="index">
                                        <div style="width: 14.26%" class="px-1">
                                            <div x-text="day"
                                                 class="text-xs font-medium text-center text-gray-800"></div>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex flex-wrap -mx-1">
                                    <template x-for="(date, dateIndex) in numberOfDays" :key="dateIndex">
                                        <div style="width: 14.28%" class="px-1 mb-1" wire:click="setDate">
                                            <div
                                                @click="
                                                getDateValue(date);
                                                setCurrentDate(date);
                                                $wire.setDate(getDateValue(date));
                                            "
                                                x-text="date"
                                                class="text-sm leading-loose text-center transition duration-100 ease-in-out rounded-full cursor-pointer"
                                                :class="{
                                                'bg-green-base text-white': isToday(date) == true,
                                                'text-gray-700 hover:bg-green-light': isToday(date) == false
                                            }"
                                            ></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex-grow"
            x-data="{
            openModal: false,
            openDoorsTab: 'daily',
            openHoursTab: 'daily',
            openSetsTab: 'daily',
            openSetClosesTab: 'daily',
            openClosesTab: 'daily',
            active: 'border-b-2 border-green-base text-green-base',
            inactive: 'text-gray-900 hover:text-gray-800'
        }">
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

                        <div class="mt-6 flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($this->topTenDoors->isNotEmpty())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th>@lang('Rank')</x-table.th>
                                                    <x-table.th>@lang('Representative')</x-table.th>
                                                    <x-table.th>@lang('Doors')</x-table.th>
                                                    <x-table.th>@lang('Region')</x-table.th>
                                                    <x-table.th>@lang('Office')</x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($this->topTenDoors as $user)
                                                    <x-table.tr
                                                        :loop="$loop"
                                                        x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})" class="cursor-pointer"
                                                    >
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            {{ $user->full_name }}
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->doors_total }}</x-table.td>
                                                        <x-table.td>{{ $user->office ? $user->office->region->name : html_entity_decode('&#8212;') }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="flex justify-center align-middle">
                                            <div class="text-sm text-center text-gray-700">
                                                <x-svg.draw.empty/>
                                                There are no top 10 doors for this period
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span
                            class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                            Top 10 Hours Worked
                        </span>

                        <div class="mt-6 flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($this->topTenHours->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th>@lang('Rank')</x-table.th>
                                                    <x-table.th>@lang('Representative')</x-table.th>
                                                    <x-table.th>@lang('Hours')</x-table.th>
                                                    <x-table.th>@lang('Region')</x-table.th>
                                                    <x-table.th>@lang('Office')</x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($this->topTenHours as $user)
                                                    <x-table.tr
                                                        :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer"
                                                    >
                                                        <x-table.td>
                                                                <span
                                                                    class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                    {{ $loop->index + 1 }}
                                                                </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->full_name }}</x-table.td>
                                                        <x-table.td>{{ $user->hours_worked_total }}</x-table.td>
                                                        <x-table.td>{{ $user->office ? $user->office->region->name : html_entity_decode('&#8212;') }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="flex justify-center align-middle">
                                            <div class="text-sm text-center text-gray-700">
                                                <x-svg.draw.empty></x-svg.draw.empty>
                                                There are no top 10 hours worked for this period.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span
                            class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                            Top 10 Sets
                        </span>

                        <div class="mt-6 flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($this->topTenSets->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th>@lang('Rank')</x-table.th>
                                                    <x-table.th>@lang('Representative')</x-table.th>
                                                    <x-table.th>@lang('Sets')</x-table.th>
                                                    <x-table.th>@lang('Region')</x-table.th>
                                                    <x-table.th>@lang('Office')</x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($this->topTenSets as $user)
                                                    <x-table.tr
                                                        :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer"
                                                    >
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->full_name }}</x-table.td>
                                                        <x-table.td>{{ $user->sets_total }}</x-table.td>
                                                        <x-table.td>{{ $user->office ? $user->office->region->name : html_entity_decode('&#8212;') }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="flex justify-center align-middle">
                                            <div class="text-sm text-center text-gray-700">
                                                <x-svg.draw.empty></x-svg.draw.empty>
                                                There are no top 10 sets for this period.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span
                            class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                            Top 10 Set Closes
                        </span>

                        <div class="mt-6 flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($this->topTenSetCloses->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th>@lang('Rank')</x-table.th>
                                                    <x-table.th>@lang('Representative')</x-table.th>
                                                    <x-table.th>@lang('Set Closes')</x-table.th>
                                                    <x-table.th>@lang('Region')</x-table.th>
                                                    <x-table.th>@lang('Office')</x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($this->topTenSetCloses as $user)
                                                    <x-table.tr
                                                        :loop="$loop"
                                                        x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer"
                                                    >
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->full_name }}</x-table.td>
                                                        <x-table.td>{{ $user->set_closes_total }}</x-table.td>
                                                        <x-table.td>{{ $user->office ? $user->office->region->name : html_entity_decode('&#8212;') }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="flex justify-center align-middle">
                                            <div class="text-sm text-center text-gray-700">
                                                <x-svg.draw.empty></x-svg.draw.empty>
                                                There are no top 10 set closes for this period
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span
                            class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                            Top 10 Closes
                        </span>

                        <div class="mt-6 flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    @if ($this->topTenCloses->count())
                                        <x-table>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th >@lang('Rank')</x-table.th>
                                                    <x-table.th >@lang('Representative')</x-table.th>
                                                    <x-table.th >@lang('Closes')</x-table.th>
                                                    <x-table.th >@lang('Region')</x-table.th>
                                                    <x-table.th >@lang('Office')</x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach ($this->topTenCloses as $user)
                                                    <x-table.tr
                                                        :loop="$loop" x-on:click="openModal = true"
                                                        wire:click="setUser({{ $user->id }})"
                                                        class="cursor-pointer"
                                                    >
                                                        <x-table.td>
                                                            <span
                                                                class="px-2 inline-flex rounded-full bg-green-base text-white">
                                                                {{ $loop->index + 1 }}
                                                            </span>
                                                        </x-table.td>
                                                        <x-table.td>{{ $user->full_name }}</x-table.td>
                                                        <x-table.td>{{ $user->closes_total }}</x-table.td>
                                                        <x-table.td>{{ $user->office ?  $user->office->name : html_entity_decode('&#8212;') }}</x-table.td>
                                                        <x-table.td>{{ $user->office->name ?? 'Without Office' }}</x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @else
                                        <div class="flex justify-center align-middle">
                                            <div class="text-sm text-center text-gray-700">
                                                <x-svg.draw.empty/>
                                                There are no top 10 closes for this period
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <x-svg.spinner
                    color="#9fa6b2"
                    class="fixed hidden left-1/2 top-1/2 w-20"
                    wire:loading.class.remove="hidden"
                />

                @if ($userId)
                    <div x-cloak x-show="openModal" wire:loading.remove
                         class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                        <div x-show="openModal" x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <div class="sm:w-1/4 relative bottom-24" x-show="openModal" x-transition:enter="ease-out duration-300"
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
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-4 py-5 sm:p-6 bg-gray-50">
                                <div class="flex justify-between">
                                    <div class="flex justify-start">
                                        <div class="flex items-center">
                                            <div>
                                                <img class="inline-block h-16 w-16 rounded-full" src="{{ $userArray['photo_url'] }}" alt=""/>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm leading-5 font-medium text-gray-700 group-hover:text-gray-900">
                                                    {{ $userArray['full_name'] }}
                                                </p>
                                                <p class="text-xs leading-4 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150">
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
                                    <div class="flex border-gray-200 border-2 m-1 h-56 rounded-lg">
                                        <div class="w-2/3" id="chartdiv"></div>
                                        <div class="w-1/3 block pt-4 space-y-1">
                                            <div class="text-sm" id="hoursWorked">0 hours worked</div>
                                            <div class="text-sm" id="doors">0 doors</div>
                                            <div class="text-sm" id="sits">0 sits</div>
                                            <div class="text-sm" id="sets">0 sets</div>
                                            <div class="text-sm" id="set_closes">0 set closes</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    const MONTH_NAMES = [
        'January', 'February', 'March',
        'April', 'May', 'June',
        'July', 'August', 'September',
        'October', 'November', 'December'
    ];
    const DAYS = [
        'Sun', 'Mon', 'Tue', 'Wed',
        'Thu', 'Fri', 'Sat'
    ];

    function app() {
        return {
            showDatepicker: true,
            datepickerValue: '',
            month: '',
            year: '',
            numberOfDays: [],
            currentDate: new Date(),
            days: DAYS,
            initDate() {
                const today = new Date();
                this.month = today.getMonth();
                this.year = today.getFullYear();

                this.datepickerValue = new Date().toDateString();
            },
            isToday(date) {
                return this.currentDate.toDateString() === new Date(this.year, this.month, date).toDateString();
            },
            setCurrentDate(date) {
                this.currentDate = new Date(this.year, this.month, date);
            },
            getDateValue(date) {
                const selectedDate = new Date(this.year, this.month, date);

                this.datepickerValue = selectedDate.toDateString();
                this.$refs.date.value = selectedDate.toDateString();
                this.showDatepicker = true;

                return this.$refs.date.value;
            },
            getNoOfDays() {
                const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                const dayOfWeek = new Date(this.year, this.month).getDay();
                const days = Array.from({ length: dayOfWeek }, () => null);

                for (let i = 1; i <= daysInMonth; i++) {
                    days.push(i);
                }

                this.numberOfDays = days;
            }
        };
    }

    am4core.ready(function () {
        // Themes begin
        window.addEventListener('setUserNumbers', event => {
            am4core.useTheme(am4themes_animated);
            // Themes end
            if (event.detail.doors && event.detail.hoursWorked && event.detail.sits && event.detail.sets && event.detail.set_closes) {
                var chart = am4core.create('chartdiv', am4charts.SlicedChart);
                chart.data = [{
                    'name': 'doors',
                    'value2': event.detail.doors
                }, {
                    'name': 'hours worked',
                    'value2': event.detail.hoursWorked
                }, {
                    'name': 'sits',
                    'value2': event.detail.sits
                }, {
                    'name': 'sets',
                    'value2': event.detail.sets
                }, {
                    'name': 'Set Closes',
                    'value2': event.detail.set_closes
                }];
                chart.logo.disabled = true;
                var series1 = chart.series.push(new am4charts.FunnelSeries());
                series1.dataFields.value = 'value2';
                series1.dataFields.category = 'name';
                series1.labels.template.disabled = true;
                document.getElementById('hoursWorked').innerHTML = `${event.detail.hoursWorked} Hours Worked`;
                document.getElementById('hoursWorked').style.color = '#67B7DC';
                document.getElementById('doors').innerHTML = `${event.detail.doors} Doors`;
                document.getElementById('doors').style.color = '#648FD5';
                document.getElementById('sits').innerHTML = `${event.detail.sits} Sits`;
                document.getElementById('sits').style.color = '#6670DB';
                document.getElementById('sets').innerHTML = `${event.detail.sets} Sets`;
                document.getElementById('sets').style.color = '#8067DC';
                document.getElementById('set_closes').innerHTML = `${event.detail.set_closes} Set Closes`;
                document.getElementById('set_closes').style.color = '#A367DC';
            } else {
                document.getElementById('chartdiv').innerHTML = 'No data to display';
                document.getElementById('chartdiv').classList.add('pt-3', 'pl-2');
            }
        });
    }); // end am4core.ready()

</script>
