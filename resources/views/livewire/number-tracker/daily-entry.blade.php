<div>
    <x-form :route="route('number-tracking.store')">
        <div class="mx-auto max-w-8xl sm:px-6 lg:px-8" x-data>
            <div class="md:flex">
                <div class="py-5 md:w-1/3 xl:w-1/4">
                    <div class="flex-row">
                        <div class="overflow-y-auto">
                            <div class="overflow-hidden">
                                <div class="flex justify-start">
                                    <x-link :href="route('number-tracking.index')" color="gray" class="inline-flex items-center text-sm font-medium leading-5 border-b-2 border-green-base hover:border-green-500">
                                        <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Back to Tracker Overview')
                                    </x-link>
                                </div>

                                <div class="antialiased sans-serif">
                                    <div x-data="app()" x-init="[initDate(), getNoOfDays()]">
                                        <div class="container mx-auto">
                                            <div class="mt-6 mb-5">
                                                <div class="relative">
                                                    <input type="hidden" wire:model="date" name="date" x-ref="date">

                                                    <div class="top-0 left-0 p-4 bg-white border-2 border-gray-200 rounded-lg">

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
                                                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                                                <div style="width: 14.28%" class="px-1 mb-1" wire:click="setDate">
                                                                    <div
                                                                        @click="getDateValue(date); setCurrentDate(date); @this.set('date', getDateValue(date)); @this.call('setDate')"
                                                                        x-text="date"
                                                                        class="text-sm leading-loose text-center transition duration-100 ease-in-out rounded-full cursor-pointer"
                                                                        :class="{
                                                                                'bg-green-base text-white': isToday(date) == true,
                                                                                'text-gray-700 hover:bg-green-light': isToday(date) == false,
                                                                                'text-red-700': isMissingDate(date) == true
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

                                @foreach($offices as $key => $office)
                                    <button
                                        type="button"
                                        class="flex justify-between w-full justify-left py-2 px-4 mb-2 bg-white rounded-lg border-gray-200 border-2 top-0 left-0 text-sm leading-5 font-medium focus:outline-none
                                            @if($officeSelected == $office->id) border-green-400 @else focus:border-gray-700 focus:shadow-outline-gray @endif transition duration-150 ease-in-out"
                                        wire:click="setOffice({{ $office }})">
                                        {{$office->region->name}} - {{ $office->name }}
                                        @if(in_array($office, $missingOffices))
                                            <div class="w-2 h-2 bg-red-600 rounded-full "></div>
                                        @endif
                                    </button>
                                @endforeach
                                <input name="officeSelected" id="officeSelected" value="{{ $officeSelected }}" class="hidden"/>

                                <div class="mt-6">
                                    <x-button type="submit" color="green" class="inline-flex w-full">
                                        Save Changes
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center px-4 py-5 h-1/2 sm:p-6 md:w-2/3 xl:w-3/4">
                    <div class="overflow-auto flex flex-row space-x-4 p-3 mb-3">
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 p-3 border-2 border-gray-200 rounded-lg">
                            <div class="text-xs font-semibold text-gray-900 uppercase">Hours Worked</div>
                            <div class="text-xl font-bold text-gray-900">{{$users->sum('dailyNumbers.0.hours_worked')}}</div>
                            <div class="flex font-semibold text-xs @if($users->sum('dailyNumbers.0.hours_worked') >= $usersLastDayEntries->sum('dailyNumbers.0.hours_worked')) text-green-base @else text-red-600 @endif">
                                @if($users->sum('dailyNumbers.0.hours_worked') >= $usersLastDayEntries->sum('dailyNumbers.0.hours_worked'))
                                    <x-svg.arrow-up class="text-green-base"/>
                                    <span>
                                        +{{$users->sum('dailyNumbers.0.hours_worked') - $usersLastDayEntries->sum('dailyNumbers.0.hours_worked')}}
                                    </span>
                                @else
                                    <x-svg.arrow-down class="text-red-600"/>
                                    <span>
                                        {{$users->sum('dailyNumbers.0.hours_worked') - $usersLastDayEntries->sum('dailyNumbers.0.hours_worked')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 p-3 border-2 border-gray-200 rounded-lg">
                            <div class="text-xs font-semibold uppercase">Doors</div>
                            <div class="text-xl font-bold">{{$users->sum('dailyNumbers.0.doors')}}</div>
                            <div class="flex font-semibold text-xs @if($users->sum('dailyNumbers.0.doors') >= $usersLastDayEntries->sum('dailyNumbers.0.doors')) text-green-base @else text-red-600 @endif">
                                @if($users->sum('dailyNumbers.0.doors') >= $usersLastDayEntries->sum('dailyNumbers.0.doors'))
                                    <x-svg.arrow-up class="text-green-base"/>
                                    <span>
                                        +{{$users->sum('dailyNumbers.0.doors') - $usersLastDayEntries->sum('dailyNumbers.0.doors')}}
                                    </span>
                                @else
                                    <x-svg.arrow-down class="text-red-600"/>
                                    <span>
                                        {{$users->sum('dailyNumbers.0.doors') - $usersLastDayEntries->sum('dailyNumbers.0.doors')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 p-3 border-2 border-gray-200 rounded-lg">
                            <div class="text-xs font-semibold text-gray-900 uppercase">Hours Knocked</div>
                            <div class="text-xl font-bold text-gray-900">{{$users->sum('dailyNumbers.0.hours_knocked')}}</div>
                            <div class="flex font-semibold text-xs @if($users->sum('dailyNumbers.0.hours_knocked') >= $usersLastDayEntries->sum('dailyNumbers.0.hours_knocked')) text-green-base @else text-red-600 @endif">
                                @if($users->sum('dailyNumbers.0.hours_knocked') >= $usersLastDayEntries->sum('dailyNumbers.0.hours_knocked'))
                                    <x-svg.arrow-up class="text-green-base"/>
                                    <span>
                                        +{{$users->sum('dailyNumbers.0.hours_knocked') - $usersLastDayEntries->sum('dailyNumbers.0.hours_knocked')}}
                                    </span>
                                @else
                                    <x-svg.arrow-down class="text-red-600"/>
                                    <span>
                                        {{$users->sum('dailyNumbers.0.hours_knocked') - $usersLastDayEntries->sum('dailyNumbers.0.hours_knocked')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 p-3 border-2 border-gray-200 rounded-lg">
                            <div class="text-xs font-semibold text-gray-900 uppercase">Sets</div>
                            <div class="text-xl font-bold text-gray-900">{{$users->sum('dailyNumbers.0.sets')}}</div>
                            <div class="flex font-semibold text-xs @if($users->sum('dailyNumbers.0.sets') >= $usersLastDayEntries->sum('dailyNumbers.0.sets')) text-green-base @else text-red-600 @endif">
                                @if($users->sum('dailyNumbers.0.sets') >= $usersLastDayEntries->sum('dailyNumbers.0.sets'))
                                    <x-svg.arrow-up class="text-green-base"/>
                                    <span>
                                        +{{$users->sum('dailyNumbers.0.sets') - $usersLastDayEntries->sum('dailyNumbers.0.sets')}}
                                    </span>
                                @else
                                    <x-svg.arrow-down class="text-red-600"/>
                                    <span>
                                        {{$users->sum('dailyNumbers.0.sets') - $usersLastDayEntries->sum('dailyNumbers.0.sets')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 p-3 border-2 border-gray-200 rounded-lg">
                            <div class="text-xs font-semibold text-gray-900 uppercase">Sats</div>
                            <div class="text-xl font-bold text-gray-900">{{$users->sum('dailyNumbers.0.sats')}}</div>
                            <div class="flex font-semibold text-xs @if($users->sum('dailyNumbers.0.sats') >= $usersLastDayEntries->sum('dailyNumbers.0.sats')) text-green-base @else text-red-600 @endif">
                                @if($users->sum('dailyNumbers.0.sats') >= $usersLastDayEntries->sum('dailyNumbers.0.sats'))
                                    <x-svg.arrow-up class="text-green-base"/>
                                    <span>
                                        +{{$users->sum('dailyNumbers.0.sats') - $usersLastDayEntries->sum('dailyNumbers.0.sats')}}
                                    </span>
                                @else
                                    <x-svg.arrow-down class="text-red-600"/>
                                    <span>
                                        {{$users->sum('dailyNumbers.0.sats') - $usersLastDayEntries->sum('dailyNumbers.0.sats')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 p-3 border-2 border-gray-200 rounded-lg">
                            <div class="text-xs font-semibold text-gray-900 uppercase">Closer Sits</div>
                            <div class="text-xl font-bold text-gray-900">{{$users->sum('dailyNumbers.0.closer_sits')}}</div>
                            <div class="flex font-semibold text-xs @if($users->sum('dailyNumbers.0.closer_sits') >= $usersLastDayEntries->sum('dailyNumbers.0.closer_sits')) text-green-base @else text-red-600 @endif">
                                @if($users->sum('dailyNumbers.0.closer_sits') >= $usersLastDayEntries->sum('dailyNumbers.0.closer_sits'))
                                    <x-svg.arrow-up class="text-green-base"/>
                                    <span>
                                        +{{$users->sum('dailyNumbers.0.closer_sits') - $usersLastDayEntries->sum('dailyNumbers.0.closer_sits')}}
                                    </span>
                                @else
                                    <x-svg.arrow-down class="text-red-600"/>
                                    <span>
                                        {{$users->sum('dailyNumbers.0.closer_sits') - $usersLastDayEntries->sum('dailyNumbers.0.closer_sits')}}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: fit-content; flex: 0 0 auto" class="w-56 p-3 border-2 border-gray-200 rounded-lg">
                                <div class="text-xs font-semibold text-gray-900 uppercase">Closes</div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-1">Set</div>
                                    <div class="text-md font-bold text-gray-900 col-span-2">{{$users->sum('dailyNumbers.0.set_closes')}}</div>
                                    <div class="flex text-xs place-self-end col-span-1 items-center">
                                        @if($users->sum('dailyNumbers.0.set_closes') - $usersLastDayEntries->sum('dailyNumbers.0.set_closes') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                                @if($users->sum('dailyNumbers.0.set_closes') - $usersLastDayEntries->sum('dailyNumbers.0.set_closes') >= 0)
                                                    text-green-base
                                                @else
                                                    text-red-600
                                                @endif">
                                            {{$users->sum('dailyNumbers.0.set_closes') - $usersLastDayEntries->sum('dailyNumbers.0.set_closes')}}
                                        </span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-1">
                                    <div class="text-sm self-center col-span-1">SG</div>
                                    <div class="text-md font-bold text-gray-900 col-span-2">{{$users->sum('dailyNumbers.0.closes')}}</div>
                                    <div class="flex text-xs place-self-end col-span-1 items-center">
                                        @if($users->sum('dailyNumbers.0.closes') - $usersLastDayEntries->sum('dailyNumbers.0.closes') >= 0)
                                            <x-svg.arrow-up class="text-green-base"/>
                                        @else
                                            <x-svg.arrow-down class="text-red-600"/>
                                        @endif
                                        <span class="
                                                @if($users->sum('dailyNumbers.0.closes') - $usersLastDayEntries->sum('dailyNumbers.0.closes') >= 0)
                                                    text-green-base
                                                @else
                                                    text-red-600
                                                @endif">
                                            {{$users->sum('dailyNumbers.0.closes') - $usersLastDayEntries->sum('dailyNumbers.0.closes')}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-20 mt-3"
                        wire:loading.class.remove="hidden"
                        wire:target="setOffice, setDate, store">
                    </x-svg.spinner>

                    <div class="w-full mt-3">
                        @if($users->count())
                            <div class="flex flex-col">
                                <div class="overflow-x-auto">
                                    <div class="inline-block min-w-full overflow-hidden align-middle">
                                        <x-table wire:loading.remove>
                                            <x-slot name="header">
                                                <x-table.th-tr>
                                                    <x-table.th by="region_member">@lang('Member')</x-table.th>
                                                    <x-table.th by="doors">@lang('Hours Worked')</x-table.th>
                                                    <x-table.th by="doors">@lang('Doors')</x-table.th>
                                                    <x-table.th by="doors">@lang('Hours Knocked')</x-table.th>
                                                    <x-table.th by="sets">@lang('Sets')</x-table.th>
                                                    <x-table.th by="sets">@lang('Sats')</x-table.th>
                                                    <x-table.th by="set_closes">@lang('Set Closes')</x-table.th>
                                                    <x-table.th by="set_closes">@lang('Closer Sits')</x-table.th>
                                                    <x-table.th by="closes">@lang('Closes')</x-table.th>
                                                </x-table.th-tr>
                                            </x-slot>
                                            <x-slot name="body">
                                                @foreach($users as $user)
                                                    <x-table.tr :loop="$loop" wire:key="user.id-{{$user->id}}">
                                                        <x-table.td>{{ $user->full_name }}</x-table.td>
                                                        <x-table.td class="flex justify-center">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][hours_worked]"
                                                                readonly
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5 pointer-events-none"
                                                                value="{{ $user->dailyNumbers->sum('hours_worked') }}"/>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][doors]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('doors') }}"/>
                                                        </x-table.td>
                                                        <x-table.td class="flex justify-center">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][hours_knocked]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('hours_knocked') }}"/>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][sets]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('sets') }}"/>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][sats]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('sats') }}"/>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][set_closes]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('set_closes') }}"/>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][closer_sits]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('closer_sits') }}"/>
                                                        </x-table.td>
                                                        <x-table.td>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                name="numbers[{{ $user->id }}][closes]"
                                                                class="block transition duration-150 ease-in-out form-input w-14 sm:text-sm sm:leading-5"
                                                                value="{{ $user->dailyNumbers->sum('closes') }}"/>
                                                        </x-table.td>
                                                    </x-table.tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    </div>
                                </div>
                            </div>
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
    </x-form>
</div>

<script>
    const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    function app() {

        return {
            showDatepicker: true,
            datepickerValue: '',
            missingDates:  [],
            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            currentDate: new Date(),

            initDate() {
                let today  = new Date();
                this.month = today.getMonth();
                this.year  = today.getFullYear();
                this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
            },

            getMissingDates() {
                let component = window.livewire.find("daily-entry-tracker");
                this.missingDates = @this.get('missingDates');
                window.livewire.emit('getMissingDates', 'Y-m-01');
                window.livewire.on('responseMissingDate', (missingDates) => {
                    this.missingDates = missingDates;
                })
            },

            isToday(date) {
                const d = new Date(this.year, this.month, date);
                return this.currentDate.toDateString() === d.toDateString() ? true : false;
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
            },

            isMissingDate(date){
                missingDates = @this.get('missingDates');
                if(date != null){
                    date = (date < 10) ? '0' + date.toString() : date.toString();
                    let month = (this.month < 9) ? '0' + (this.month + 1).toString() : (this.month + 1).toString()
                    let searchDate = this.year + '-' + month + '-' + date;
                    let response;
                    for(let missingDate of missingDates) {
                        response = missingDate == searchDate
                        if (response == true){
                            break;
                        }
                    };
                    return response;
                }else{
                    return false;
                }

            },
        }
    }

</script>
