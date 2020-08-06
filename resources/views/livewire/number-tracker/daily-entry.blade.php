<div>
<x-form :route="route('number-tracking.store')">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex">
            <div class="px-4 py-5 sm:px-6 w-1/3">
                <div class="flex justify-start">
                    <h3 class="text-lg text-gray-900">Number Tracker</h3>
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
                                            <template x-for="blankday in blankdays">
                                                <div 
                                                    style="width: 14.28%"
                                                    class="text-center border p-1 border-transparent text-sm"	
                                                ></div>
                                            </template>	
                                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">	
                                                <div style="width: 14.28%" class="px-1 mb-1" wire:click="setDate">
                                                    <div
                                                        @click="getDateValue(date); setCurrentDate(date); @this.set('date', getDateValue(date)); @this.call('setDate')"
                                                        x-text="date"
                                                        class="cursor-pointer text-center text-sm leading-none rounded-full leading-loose transition ease-in-out duration-100"
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

                @foreach($regions as $region)
                    <button 
                        type="button"
                        class="inline-flex w-full justify-left py-2 px-4 mb-2 bg-white rounded-lg border-gray-200 border-2 top-0 left-0 text-sm leading-5 font-medium focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out"
                        wire:click="setRegion({{ $region->id }})"
                        :class="{
                                    'border-green-400': {{ $regionSelected }} == {{ $region->id }}
                            }"
                    >
                        {{ $region->name }}
                    </button>
                @endforeach

                <div class="mt-6">
                    <button type="submit" class="inline-flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-base hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                        Save Changes
                    </button>
                </div>
            </div>

            <div class="px-4 py-5 sm:p-6 w-2/3">
                
                <div class="mt-6">
                    <div class="align-middle inline-block min-w-full overflow-hidden">

                    <div class="flex justify-between mt-3">
                            <div class="w-full grid md:grid-cols-6 sm:grid-cols-3 md:col-gap-4 sm:col-gap-1 row-gap-2">
                                <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                    <div class="text-xs font-semibold uppercase">Doors</div>
                                    <div class="text-xl font-bold">1752</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current"></path>
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6"></use>
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                                <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                    <div class="text-xs text-gray-900 font-semibold uppercase">Hours</div>
                                    <div class="text-xl text-gray-900 font-bold">153</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current"></path>
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6"></use>
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                                <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                    <div class="text-xs text-gray-900 font-semibold uppercase">Sets</div>
                                    <div class="text-xl text-gray-900 font-bold">113</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current"></path>
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6"></use>
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                                <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                    <div class="text-xs text-gray-900 font-semibold uppercase">Sits</div>
                                    <div class="text-xl text-gray-900 font-bold">68</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current"></path>
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6"></use>
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                                <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                    <div class="text-xs text-gray-900 font-semibold uppercase">Set closes</div>
                                    <div class="text-xl text-gray-900 font-bold">6</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current"></path>
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6"></use>
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                                <div class="col-span-1 border-2 border-gray-200 rounded-lg p-3">
                                    <div class="text-xs text-gray-900 font-semibold uppercase">Closes</div>
                                    <div class="text-xl text-gray-900 font-bold">5</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current"></path>
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6"></use>
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div wire:loading class="mt-6">
                            Loading...
                        </div>
                        <table class="min-w-full mt-3" wire:loading.remove>
                        <thead>
                            <tr>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Region Member
                            </th>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Doors
                            </th>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Hours
                            </th>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Sets
                            </th>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Sits
                            </th>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Set Closes
                            </th>
                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                Closes
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-gray-200 border-2 rounded-lg">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    {{ $user->first_name . ' ' . $user->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 w-35">
                                    <input 
                                        type="number" 
                                        min="0" 
                                        name="numbers[{{ $user->id }}][doors]" 
                                        class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="{{ $user->doors }}"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    <input 
                                        type="number" 
                                        min="0" 
                                        step="any" 
                                        name="numbers[{{ $user->id }}][hours]" 
                                        class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="{{ $user->hours }}"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    <input 
                                        type="number" 
                                        min="0"
                                        name="numbers[{{ $user->id }}][sets]" 
                                        class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="{{ $user->sets }}"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    <input 
                                        type="number" 
                                        min="0"
                                        name="numbers[{{ $user->id }}][sits]" 
                                        class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="{{ $user->sits }}"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    <input 
                                        type="number" 
                                        min="0"
                                        name="numbers[{{ $user->id }}][set_closes]" 
                                        class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="{{ $user->set_closes }}"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    <input 
                                        type="number" 
                                        min="0"
                                        name="numbers[{{ $user->id }}][closes]" 
                                        class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        value="{{ $user->closes }}"
                                    >
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
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

            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            currentDate: new Date(),

            initDate() {
                let today = new Date();
                this.month = today.getMonth();
                this.year = today.getFullYear();
                this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
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
                let blankdaysArray = [];
                for ( var i=1; i <= dayOfWeek; i++) {
                    blankdaysArray.push(i);
                }

                let daysArray = [];
                for ( var i=1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }

                this.blankdays = blankdaysArray;
                this.no_of_days = daysArray;
            }
        }
    }
</script>