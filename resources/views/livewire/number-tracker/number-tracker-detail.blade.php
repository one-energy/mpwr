<div>
    <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
        <div class="md:flex">
            <div class="py-5 overflow-hidden md:w-1/3 xl:w-1/4">
                <div class="flex justify-start">
                    <h3 class="text-lg text-gray-900">Number Tracker</h3>
                </div>

                <ul class="flex mt-3 border-b">
                    <li class="mr-4 -mb-px">
                        <a  class="bg-white inline-block
                                    @if($period == 'd')
                                        border-b-2 border-green-base text-green-base
                                    @else text-gray-900
                                        hover:text-gray-800
                                    @endIf
                                    py-2 text-sm font-semibold"
                            href="javascript:void(0);"
                            wire:click="setPeriod('d')">Daily
                        </a>
                    </li>
                    <li class="mr-4">
                        <a  class="bg-white inline-block
                                    @if($period == 'w')
                                        border-b-2 border-green-base text-green-base
                                    @else
                                        text-gray-900 hover:text-gray-800
                                    @endIf
                                    py-2 text-sm font-semibold"
                            href="javascript:void(0);"
                            wire:click="setPeriod('w')">Weekly
                        </a>
                    </li>
                    <li class="mr-4">
                        <a  class="bg-white inline-block
                                    @if($period == 'm')
                                        border-b-2 border-green-base text-green-base
                                    @else
                                        text-gray-900 hover:text-gray-800
                                    @endIf
                                    py-2 text-sm font-semibold"
                            href="javascript:void(0);"
                            wire:click="setPeriod('m')">Monthly</a>
                    </li>
                    <li>
                        <x-svg.spinner
                            color="#9fa6b2"
                            class="relative hidden w-6 top-2"
                            wire:loading.class.remove="hidden" wire:target="setPeriod">
                        </x-svg.spinner>
                    </li>
                </ul>

                <div class="antialiased sans-serif">
                    <div x-data="app()" x-init="[initDate(), getNoOfDays()]">
                        <div class="container mx-auto">
                            <div class="mt-6 mb-5">
                                <div class="relative">
                                    <input type="hidden" name="date" x-ref="date">

                                    <div
                                        class="top-0 left-0 p-4 bg-white border-2 border-gray-200 rounded-lg">

                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
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
                                                    <svg class="inline-flex w-6 h-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
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
                                                    <svg class="inline-flex w-6 h-6 text-gray-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap mb-3 -mx-1">
                                            <template x-for="(day, index) in DAYS" :key="index">
                                                <div style="width: 14.26%" class="px-1">
                                                    <div
                                                        x-text="day"
                                                        class="text-xs font-medium text-center text-gray-800"></div>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="flex flex-wrap -mx-1">
                                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex" >
                                                <div style="width: 14.28%" class="px-1 mb-1" wire:click="setDate">
                                                    <div
                                                        @click="getDateValue(date); setCurrentDate(date); @this.set('date', getDateValue(date)); @this.call('setDate')"
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

                <x-number-tracker.leaderboard-card
                    :trackers="$this->topTenTrackers"
                    :pills="$this->pills"
                />
            </div>

            <div class="px-4 py-5 sm:p-6 md:w-2/3 xl:w-3/4">
                <div class="justify-center w-full">
                    <div class="flex justify-between mt-6 md:mt-12">
                        <div class="grid w-full grid-cols-2 row-gap-2 col-gap-1 md:grid-cols-4 md:col-gap-4">
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">D.P.S</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{$numbersTracked->sum('sets') ? number_format($numbersTracked->sum('doors')/$numbersTracked->sum('sets'), 0) : '-'}}
                                </div>
                            </div>
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">H.P. Set</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{$numbersTracked->sum('sets') ? number_format($numbersTracked->sum('hours')/$numbersTracked->sum('sets'), 2) : '-'}}
                                </div>
                            </div>
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">Sit Ratio</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{$numbersTracked->sum('sets') ? (number_format(($numbersTracked->sum('sits') + $numbersTracked->sum('set_sits'))/$numbersTracked->sum('sets'), 2) * 100) . '%' : '-'}}
                                </div>
                            </div>
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">Close Ratio</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{ $numbersTracked->sum('sits') || $numbersTracked->sum('set_sits')  ? ( number_format(($numbersTracked->sum('closes') + $numbersTracked->sum('set_closes') ) / ($numbersTracked->sum('set_sits') + $numbersTracked->sum('sits')), 2) * 100) . '%' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-start mt-6">
                        <h2 class="text-lg text-gray-900">Total Overviews</h2>
                    </div>

                    <div class="flex justify-between mt-3">
                        <div class="grid w-full grid-cols-6 row-gap-2 col-gap-1 xl:grid-cols-12 md:col-gap-4">
                            <div class="col-span-2 xl:col-span-2 border-2 border-gray-400 rounded-sm p-3 space-y-3" wire:click="setFilterBy('doors')">
                                <div class="text-base font-semibold uppercase">Doors</div>
                                <div class="text-xl font-bold">{{$numbersTracked->sum('doors')}}</div>
                                <div class="flex text-xs font-semibold text-green-base">
                                    @if($numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors') >= 0)
                                        <x-svg.arrow-up class="text-green-base"/>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"/>
                                    @endif
                                    <span class="
                                        @if($numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors') >= 0)
                                            text-green-base
                                        @else
                                            text-red-600
                                        @endif
                                        text-base
                                    ">
                                        {{$numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-2 xl:col-span-2 border-2 border-gray-400 rounded-sm p-3 space-y-3" wire:click="setFilterBy('hours')">
                                <div class="text-base font-semibold text-gray-900 uppercase">Hours</div>
                                <div class="text-xl font-bold text-gray-900">{{$numbersTracked->sum('hours')}}</div>
                                <div class="flex text-xs font-semibold text-green-base">
                                    @if($numbersTracked->sum('hours') - $numbersTrackedLast->sum('hours') >= 0)
                                        <x-svg.arrow-up class="text-green-base"/>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"/>
                                    @endif
                                    <span class="
                                        @if($numbersTracked->sum('hours') - $numbersTrackedLast->sum('hours') >= 0)
                                            text-green-base
                                        @else
                                            text-red-600
                                        @endif
                                        text-base
                                    ">
                                        {{$numbersTracked->sum('hours') - $numbersTrackedLast->sum('hours')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-2 xl:col-span-2 border-2 border-gray-400 rounded-sm p-3 space-y-3" wire:click="setFilterBy('sets')">
                                <div class="text-base font-semibold text-gray-900 uppercase">Sets</div>
                                <div class="text-xl font-bold text-gray-900">{{$numbersTracked->sum('sets')}}</div>
                                <div class="flex text-xs font-semibold text-green-base">
                                    @if($numbersTracked->sum('sets') - $numbersTrackedLast->sum('sets') >= 0)
                                        <x-svg.arrow-up class="text-green-base"/>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"/>
                                    @endif
                                    <span class="
                                        @if($numbersTracked->sum('sets') - $numbersTrackedLast->sum('sets') >= 0)
                                            text-green-base
                                        @else
                                            text-red-600
                                        @endif
                                        text-base
                                    ">
                                        {{$numbersTracked->sum('sets') - $numbersTrackedLast->sum('sets')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-3 xl:col-span-3 border-2 border-gray-400 rounded-sm p-3 space-y-3" wire:click="setFilterBy('sits')">
                                <div class="text-base font-semibold text-gray-900 uppercase">Sits</div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>Set</span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$numbersTracked->sum('set_sits')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($numbersTracked->sum('set_sits') - $numbersTrackedLast->sum('set_sits') >= 0)
                                            <x-svg.arrow-up class="text-green-base text-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                                @if($numbersTracked->sum('set_sits') - $numbersTrackedLast->sum('set_sits') >= 0)
                                                    text-green-base
                                                @else
                                                    text-red-600
                                                @endif
                                                text-base
                                        ">
                                            {{$numbersTracked->sum('set_sits') - $numbersTrackedLast->sum('set_sits')}}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>SG</span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$numbersTracked->sum('sits')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($numbersTracked->sum('sits') - $numbersTrackedLast->sum('sits') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                                @if($numbersTracked->sum('sits') - $numbersTrackedLast->sum('sits') >= 0)
                                                    text-green-base
                                                @else
                                                    text-red-600
                                                @endif
                                                text-base
                                        ">
                                            {{$numbersTracked->sum('sits') - $numbersTrackedLast->sum('sits')}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 xl:col-span-3 border-2 border-gray-400 rounded-sm p-3 space-y-3" wire:click="setFilterBy('closes')">
                                <div class="text-base font-semibold text-gray-900 uppercase">Closes</div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>Set</span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$numbersTracked->sum('set_closes')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($numbersTracked->sum('set_closes') - $numbersTrackedLast->sum('set_closes') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                            @if($numbersTracked->sum('set_closes') - $numbersTrackedLast->sum('set_closes') >= 0)
                                                text-green-base
                                            @else
                                                text-red-600
                                            @endif
                                            text-base
                                        ">
                                            {{$numbersTracked->sum('set_closes') - $numbersTrackedLast->sum('set_closes')}}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>
                                            SG
                                        </span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$numbersTracked->sum('closes')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($numbersTracked->sum('closes') - $numbersTrackedLast->sum('closes') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                            @if($numbersTracked->sum('closes') - $numbersTrackedLast->sum('closes') >= 0)
                                                text-green-base
                                            @else
                                                text-red-600
                                            @endif
                                            text-base
                                        ">
                                            {{$numbersTracked->sum('closes') - $numbersTrackedLast->sum('closes')}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-start gap-4 mt-6">
                        <div class="col-span-1 border-2
                            cursor-pointer
                            rounded-lg p-1" wire:click="changeOrder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                @if($order == 'desc')
                                    <path d="M6 3l-6 8h4v10h4v-10h4l-6-8zm16 14h-8v-2h8v2zm2 2h-10v2h10v-2zm-4-8h-6v2h6v-2zm-2-4h-4v2h4v-2zm-2-4h-2v2h2v-2z"/>
                                @else
                                    <path d="M6 21l6-8h-4v-10h-4v10h-4l6 8zm16-12h-8v-2h8v2zm2-6h-10v2h10v-2zm-4 8h-6v2h6v-2zm-2 4h-4v2h4v-2zm-2 4h-2v2h2v-2z"/>
                                @endif
                            </svg>
                        </div>
                        <div class="p-1">
                            <h2 class="text-lg text-gray-900">All Members</h2>
                        </div>
                    </div>
                    <div class="flex justify-center w-full">
                        <x-svg.spinner
                            color="#9fa6b2"
                            class="self-center hidden w-20 mt-3"
                            wire:loading.class.remove="hidden" wire:target="setDate, setPeriod, addFilter, removeFilter">
                        </x-svg.spinner>

                        <div class="w-full mt-6"wire:loading.remove wire:target="setDate, setPeriod, addFilter, removeFilter">
                            <div class="flex flex-col">
                                <div class="overflow-x-auto">
                                    <div class="inline-block min-w-full overflow-hidden align-middle">
                                        @if(count($numbersTracked))
                                            <x-table>
                                                <x-slot name="header">
                                                    <x-table.th-tr>
                                                        @if(user()->role == 'Admin' || user()->role == 'Owner')
                                                            <x-table.th by="deparmtent">
                                                                @lang('Department')
                                                            </x-table.th>
                                                        @endif
                                                        <x-table.th by="region_number">
                                                            @lang('Member')
                                                        </x-table.th>
                                                        <x-table.th by="doors">
                                                            @lang('Doors')
                                                        </x-table.th>
                                                        <x-table.th by="hours">
                                                            @lang('Hours')
                                                        </x-table.th>
                                                        <x-table.th by="sets">
                                                            @lang('Sets')
                                                        </x-table.th>
                                                        <x-table.th by="set_sits">
                                                            @lang('Set Sits')
                                                        </x-table.th>
                                                        <x-table.th by="sits">
                                                            @lang('Sits')
                                                        </x-table.th>
                                                        <x-table.th by="set_closes">
                                                            @lang('Set Closes')
                                                        </x-table.th>
                                                        <x-table.th by="closes">
                                                            @lang('Closes')
                                                        </x-table.th>
                                                    </x-table.th-tr>
                                                </x-slot>
                                                <x-slot name="body">
                                                    @foreach($numbersTracked as $row)
                                                        <x-table.tr :loop="$loop">
                                                            @if(user()->role == 'Admin' || user()->role == 'Owner')
                                                                <x-table.td>{{ $row->user->department->name }}</x-table.td>
                                                            @endif
                                                            <x-table.td>{{ $row['first_name'] . ' ' .  $row['last_name']}}</x-table.td>
                                                            <x-table.td>{{ $row['doors'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['hours'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['sets'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['set_sits'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['sits'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['set_closes'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['closes'] ?? 0 }}</x-table.td>
                                                        </x-table.tr>
                                                    @endforeach
                                                </x-slot>
                                            </x-table>
                                        @else
                                            <div class="h-96 ">
                                                <div class="flex justify-center align-middle">
                                                    <div class="text-sm text-center text-gray-700">
                                                        <x-svg.draw.empty></x-svg.draw.empty>
                                                        No data yet.
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
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    function app() {
        return {
            showDatepicker: true,
            datepickerValue: '',
            month: '',
            year: '',
            no_of_days: [],
            currentDate: new Date(),

            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            initDate() {
                let today = new Date();
                this.month = today.getMonth();
                this.year = today.getFullYear();
                this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
            },
            isToday(date) {
                const d = new Date(this.year, this.month, date);
                return this.currentDate.toDateString() === d.toDateString() ? true : false;;
            },
            setCurrentDate(date) {
                this.currentDate = new Date(this.year, this.month, date);
            },
            getDateValue(date) {
                let selectedDate = new Date(this.year, this.month, date);
                this.datepickerValue = selectedDate.toDateString();

                this.$refs.date.value = selectedDate.toDateString();

                this.showDatepicker = true;
                return this.$refs.date.value;
            },
            getNoOfDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                // find where to start calendar day of week
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let daysArray = [];
                for ( var i=1; i <= dayOfWeek; i++) {
                    daysArray.push(null);
                }
                for ( var i=1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }
                this.no_of_days = daysArray;
            }
        }
    }
</script>
