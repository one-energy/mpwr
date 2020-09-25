<div>
    <x-form :route="route('number-tracking.store')">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
                <div class="py-5 md:w-1/3 LG:1/4">
                    <div class="flex-row">
                        <div class="overflow-y-auto">
                            <div class="overflow-hidden">
                                <div class="flex justify-start">
                                    <x-link :href="route('number-tracking.index')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                                        <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Back to Tracker Overview')
                                    </x-link>
                                </div>
    
                                <!-- component -->
                                <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
                                <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    
                                <div class="antialiased sans-serif">
                                    <div x-data="app()" x-init="[initDate(), getNoOfDays()]">
                                        <div class="container mx-auto">
                                            <div class="mb-5 mt-6">
                                                <div class="relative">
                                                    <input type="hidden" wire:model="date" name="date" x-ref="date">
    
                                                    <div class="bg-white rounded-lg border-gray-200 border-2 p-4 top-0 left-0">
    
                                                        <div class="flex justify-between items-center mb-2">
                                                            <div>
                                                                <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                                                <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                                            </div>
                                                            <div>
                                                                <button 
                                                                    type="button"
                                                                    class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full" 
                                                                    :class="{'cursor-not-allowed opacity-25': month == 0 }"
                                                                    :disabled="month == 0 ? true : false"
                                                                    @click="month--; getNoOfDays(); ">
                                                                    <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                                    </svg>
                                                                </button>
                                                                <button 
                                                                    type="button"
                                                                    class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full" 
                                                                    :class="{'cursor-not-allowed opacity-25': month == 11 }"
                                                                    :disabled="month == 11 ? true : false"
                                                                    @click="month++; getNoOfDays()">
                                                                    <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                                                        class="text-gray-800 font-medium text-center text-xs"></div>
                                                                </div>
                                                            </template>
                                                        </div>
    
                                                        <div class="flex flex-wrap -mx-1">
                                                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">	
                                                                <div style="width: 14.28%" class="px-1 mb-1" wire:click="setDate">
                                                                    <div
                                                                        @click="getDateValue(date); setCurrentDate(date); @this.set('date', getDateValue(date)); @this.call('setDate')"
                                                                        x-text="date"
                                                                        class="cursor-pointer text-center text-sm rounded-full leading-loose transition ease-in-out duration-100"
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
                                        wire:click="setOffice({{ $office->id }})">
                                        {{ $office->name }}
                                        @if(in_array($office, $missingOffices))
                                            <div class=" w-2 h-2 rounded-full bg-red-600 "></div>
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

                <div class="flex flex-wrap justify-center px-4 py-5 sm:p-6 md:w-2/3 lg:3/4">
                    <div class="mt-11 w-full xl:px-0 lg:px-24 md:px-0">
                        <div class="grid xl:grid-cols-6 grid-cols-3 md:col-gap-4 col-gap-1 row-gap-2">
                            <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                <div class="text-xs font-semibold uppercase">Doors</div>
                                <div class="text-xl font-bold">{{$users->sum('doors')}}</div>
                                <div class="flex font-semibold text-xs @if($users->sum('doors') >= $usersLastDayEntries->sum('doors')) text-green-base @else text-red-600 @endif">
                                    @if($users->sum('doors') >= $usersLastDayEntries->sum('doors'))
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                        <span>
                                            +{{$users->sum('doors') - $usersLastDayEntries->sum('doors')}}
                                        </span>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>
                                        <span>
                                            {{$users->sum('doors') - $usersLastDayEntries->sum('doors')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Hours</div>
                                <div class="text-xl text-gray-900 font-bold">{{$users->sum('hours')}}</div>
                                <div class="flex font-semibold text-xs @if($users->sum('hours') >= $usersLastDayEntries->sum('hours')) text-green-base @else text-red-600 @endif">
                                    @if($users->sum('hours') >= $usersLastDayEntries->sum('hours'))
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                        <span>                                                                            
                                            +{{$users->sum('hours') - $usersLastDayEntries->sum('hours')}}
                                        </span>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>
                                        <span>
                                            {{$users->sum('hours') - $usersLastDayEntries->sum('hours')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Sets</div>
                                <div class="text-xl text-gray-900 font-bold">{{$users->sum('sets')}}</div>
                                <div class="flex font-semibold text-xs @if($users->sum('sets') >= $usersLastDayEntries->sum('sets')) text-green-base @else text-red-600 @endif">
                                    @if($users->sum('sets') >= $usersLastDayEntries->sum('sets'))
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                        <span>
                                            +{{$users->sum('sets') - $usersLastDayEntries->sum('sets')}}
                                        </span>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>
                                        <span>    
                                            {{$users->sum('sets') - $usersLastDayEntries->sum('sets')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Sits</div>
                                <div class="text-xl text-gray-900 font-bold">{{$users->sum('sits')}}</div>
                                <div class="flex font-semibold text-xs @if($users->sum('sits') >= $usersLastDayEntries->sum('sits')) text-green-base @else text-red-600 @endif">
                                    @if($users->sum('sits') >= $usersLastDayEntries->sum('sits'))
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                        <span>
                                            +{{$users->sum('sits') - $usersLastDayEntries->sum('sits')}}
                                        </span>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>
                                        <span>
                                            {{$users->sum('sits') - $usersLastDayEntries->sum('sits')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Set closes</div>
                                <div class="text-xl text-gray-900 font-bold">{{$users->sum('set_closes')}}</div>
                                <div class="flex font-semibold text-xs @if($users->sum('set_closes') >= $usersLastDayEntries->sum('set_closes')) text-green-base @else text-red-600 @endif">
                                    @if($users->sum('set_closes') >= $usersLastDayEntries->sum('set_closes'))
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                        <span>
                                            +{{$users->sum('set_closes') - $usersLastDayEntries->sum('set_closes')}}
                                        </span>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>
                                        <span>
                                            {{$users->sum('set_closes') - $usersLastDayEntries->sum('set_closes')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Closes</div>
                                <div class="text-xl text-gray-900 font-bold">{{$users->sum('closes')}}</div>
                                <div class="flex font-semibold text-xs @if($users->sum('closes') >= $usersLastDayEntries->sum('closes')) text-green-base @else text-red-600 @endif">
                                    @if($users->sum('closes') >= $usersLastDayEntries->sum('closes'))
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                        <span>
                                            +{{$users->sum('closes') - $usersLastDayEntries->sum('closes')}}
                                        </span>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>
                                        <span>
                                            {{$users->sum('closes') - $usersLastDayEntries->sum('closes')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-svg.spinner 
                        color="#9fa6b2" 
                        class="self-center hidden w-20 mt-3" 
                        wire:loading.class.remove="hidden">
                    </x-svg.spinner>

                    <div class="mt-3 w-full">
                        <div class="flex flex-col">
                            <div class="overflow-x-auto">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    <x-table wire:loading.remove>
                                        <x-slot name="header">
                                            <x-table.th-tr>
                                                <x-table.th by="region_member">
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
                                            @foreach($users as $user)
                                                <x-table.tr :loop="$loop">
                                                    <x-table.td>
                                                        {{ $user->first_name . ' ' . $user->last_name }}
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <input
                                                            type="number" 
                                                            min="0" 
                                                            name="numbers[{{ $user->id }}][doors]" 
                                                            class="form-input block w-14 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                            value="{{ $user->doors }}"/>
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <input
                                                            type="number" 
                                                            min="0" 
                                                            max="24"
                                                            oninvalid="this.setCustomValidity('Value must be less than or equal 24')" 
                                                            onchange="this.setCustomValidity('')"
                                                            step="any" 
                                                            name="numbers[{{ $user->id }}][hours]" 
                                                            class="form-input block w-14 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                            value="{{ $user->hours }}"/>
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <input 
                                                            type="number" 
                                                            min="0"
                                                            name="numbers[{{ $user->id }}][sets]" 
                                                            class="form-input block w-14 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                            value="{{ $user->sets }}"/>
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <input 
                                                            type="number" 
                                                            min="0"
                                                            name="numbers[{{ $user->id }}][sits]" 
                                                            class="form-input block w-14 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                            value="{{ $user->sits }}"/>
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <input 
                                                            type="number" 
                                                            min="0"
                                                            name="numbers[{{ $user->id }}][set_closes]" 
                                                            class="form-input block w-14 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                            value="{{ $user->set_closes }}"/>
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <input 
                                                            type="number" 
                                                            min="0"
                                                            name="numbers[{{ $user->id }}][closes]" 
                                                            class="form-input block w-14 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                                            value="{{ $user->closes }}"/>
                                                    </x-table.td>
                                                </x-table.tr>
                                            @endforeach
                                        </x-slot>
                                    </x-table>
                                </div>
                            </div>
                        </div>
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
                debugger;
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
                    let month = (this.month < 10) ? '0' + (this.month + 1).toString() : (this.month + 1).toString()
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

