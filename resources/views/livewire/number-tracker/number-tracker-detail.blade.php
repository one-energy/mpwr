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
                                    {{$this->getDps()}}
                                </div>
                            </div>
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">H.P. Set</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{$this->getHps()}}
                                </div>
                            </div>
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">Sit Ratio</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{$this->getSitRatio()}}
                                </div>
                            </div>
                            <div class="col-span-1 p-3 rounded-sm bg-green-light space-y-3">
                                <div class="text-base font-semibold uppercase text-green-base">Close Ratio</div>
                                <div class="text-xl font-bold text-green-base">
                                    {{$this->getCloseRatio()}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-start mt-6">
                        <h2 class="text-lg text-gray-900">Total Overviews</h2>
                    </div>

                    <div class="flex justify-between mt-3">
                        <div class="grid w-full grid-cols-6 row-gap-2 col-gap-1 xl:grid-cols-12 md:col-gap-4">
                            <div class="col-span-2 xl:col-span-2 border-2 border-gray-200 rounded-sm p-3 space-y-3">
                                @if(!$loading)
                                    <div class="text-base font-semibold uppercase">Doors</div>
                                    <div class="text-xl font-bold">{{$this->getNumberTrackerSumOf('doors')}}</div>
                                    <div class="flex text-xs font-semibold text-green-base">
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('doors') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                            @if($this->getNumberTrackerDifferenceToLasNumbersOf('doors') >= 0)
                                                text-green-base
                                            @else
                                                text-red-600
                                            @endif
                                            text-base
                                        ">
                                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('doors')}}
                                        </span>
                                    </div>
                                @else
                                    <x-card-pulse-loading/>
                                @endif
                            </div>
                            <div class="col-span-2 xl:col-span-2 border-2 border-gray-200 rounded-sm p-3 space-y-3" >
                                <div wire:loading.remove class="text-base font-semibold text-gray-900 uppercase">Hours</div>
                                <div wire:loading.remove class="text-xl font-bold text-gray-900">{{$this->getNumberTrackerSumOf('hours')}}</div>
                                <div wire:loading.remove class="flex text-xs font-semibold text-green-base">
                                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('hours') >= 0)
                                        <x-svg.arrow-up class="text-green-base"/>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"/>
                                    @endif
                                    <span class="
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('hours') >= 0)
                                            text-green-base
                                        @else
                                            text-red-600
                                        @endif
                                        text-base
                                    ">
                                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('hours')}}
                                    </span>
                                </div>
                                <x-card-pulse-loading wire:loading.flex/>
                            </div>
                            <div class="col-span-2 xl:col-span-2 border-2 border-gray-200 rounded-sm p-3 space-y-3" >
                                <div class="text-base font-semibold text-gray-900 uppercase">Sets</div>
                                <div class="text-xl font-bold text-gray-900">{{$this->getNumberTrackerSumOf('sets')}}</div>
                                <div class="flex text-xs font-semibold text-green-base">
                                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('sets') >= 0)
                                        <x-svg.arrow-up class="text-green-base"/>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"/>
                                    @endif
                                    <span class="
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('sets')>= 0)
                                            text-green-base
                                        @else
                                            text-red-600
                                        @endif
                                        text-base
                                    ">
                                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('sets')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-3 xl:col-span-3 border-2 border-gray-200 rounded-sm p-3 space-y-3" >
                                <div class="text-base font-semibold text-gray-900 uppercase">Sits</div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>Set</span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$this->getNumberTrackerSumOf('setSits')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('setSits') >= 0)
                                            <x-svg.arrow-up class="text-green-base text-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                                @if($this->getNumberTrackerDifferenceToLasNumbersOf('setSits') >= 0)
                                                    text-green-base
                                                @else
                                                    text-red-600
                                                @endif
                                                text-base
                                        ">
                                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('setSits')}}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>SG</span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$this->getNumberTrackerSumOf('sits')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('sits')>= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                                @if($this->getNumberTrackerDifferenceToLasNumbersOf('sits') >= 0)
                                                    text-green-base
                                                @else
                                                    text-red-600
                                                @endif
                                                text-base
                                        ">
                                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('sits')}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-3 xl:col-span-3 border-2 border-gray-200 rounded-sm p-3 space-y-3" >
                                <div class="text-base font-semibold text-gray-900 uppercase">Closes</div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>Set</span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$this->getNumberTrackerSumOf('setCloses')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('setCloses') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                            @if($this->getNumberTrackerDifferenceToLasNumbersOf('setCloses') >= 0)
                                                text-green-base
                                            @else
                                                text-red-600
                                            @endif
                                            text-base
                                        ">
                                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('setCloses')}}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-3">
                                        <span>
                                            SG
                                        </span>
                                        <span class="text-xl font-bold text-gray-900 ml-2">
                                            {{$this->getNumberTrackerSumOf('closes')}}
                                        </span>
                                    </div>
                                    <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('closes') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                            @if($this->getNumberTrackerDifferenceToLasNumbersOf('closes') >= 0)
                                                text-green-base
                                            @else
                                                text-red-600
                                            @endif
                                            text-base
                                        ">
                                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('closes')}}
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
                                        @if(count($regions))
                                            <livewire:components.number-tracker-detail-accordion-table :period="$period" :selectedDate="$dateSelected" wire:key="now()"/>
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
