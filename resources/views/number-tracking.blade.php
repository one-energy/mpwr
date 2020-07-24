
<x-app.auth :title="__('Number Tracking')">
    <div>
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
                                        <input type="hidden" name="date" x-ref="date">

                                        <div 
                                            class="bg-white rounded-lg border-gray-200 border-2 p-4 top-0 left-0">

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
                                                        @click="month--; getNoOfDays()">
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
                                                    <div style="width: 14.28%" class="px-1 mb-1">
                                                        <div
                                                            @click="getDateValue(date)"
                                                            x-text="date"
                                                            class="cursor-pointer text-center text-sm leading-none rounded-full leading-loose transition ease-in-out duration-100"
                                                            :class="{'bg-green-base text-white': isToday(date) == true, 'text-gray-700 hover:bg-green-light': isToday(date) == false }"	
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

                    <div class="border-gray-200 border-2 p-4 rounded-lg">
                        <span>
                            Filters
                        </span>
                        <div class="pt-2 relative mx-auto text-gray-600">
                            <input class="border-2 border-gray-300 bg-white h-10 w-full px-5 pr-16 rounded-lg text-sm focus:outline-none"
                              type="search" name="search" placeholder="Search by Keyword">
                            <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                              <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                width="512px" height="512px">
                                <path
                                  d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                              </svg>
                            </button>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="inline-flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="px-4 py-5 sm:p-6 md:w-2/3">
                    <div class="overflow-y-auto">
                        <div class="flex justify-between md:mt-12 mt-6">
                            <div class="w-full grid md:grid-cols-4 grid-cols-2 md:col-gap-4 col-gap-1 row-gap-2">
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">D.P.S</div>
                                    <div class="text-xl text-green-base font-bold">14.6</div>
                                </div>
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">H.P. Set</div>
                                    <div class="text-xl text-green-base font-bold">1.52</div>
                                </div>
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">Sit Ratio</div>
                                    <div class="text-xl text-green-base font-bold">0.52</div>
                                </div>
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">Close Ratio</div>
                                    <div class="text-xl text-green-base font-bold">0.15</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-start mt-6">
                            <h2 class="text-lg text-gray-900">Total Overviews</h2>
                        </div>

                        <div class="flex justify-between mt-3">
                            <div class="w-full grid md:grid-cols-6 grid-cols-3 md:col-gap-4 col-gap-1 row-gap-2">
                                <div class="col-span-1 border-2 border-green-base bg-green-light rounded-lg p-3">
                                    <div class="text-xs font-semibold uppercase">Doors</div>
                                    <div class="text-xl font-bold">1752</div>
                                    <div class="flex font-semibold text-xs text-green-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6" />
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
                                        <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6" />
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
                                        <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6" />
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
                                        <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6" />
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
                                        <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6" />
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
                                        <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                                            <symbol id="arrow" viewBox="0 0 24 24">
                                            <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                                            </symbol>
                                            <use xlink:href="#arrow" width="8" height="8" y="6" x="6" />
                                        </svg>
                                        <span>
                                            +500
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end items-end">
                            <a href="#" class="py-1 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-green-base bg-green-light hover:bg-green-base hover:text-green-dark focus:outline-none focus:border-gree-base focus:shadow-outline-green transition duration-150 ease-in-out">
                                Edit
                            </a>
                            <a href="#" class="ml-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" class="border-2 border-green-base rounded-md p-1">
                                    <symbol id="download" viewBox="0 0 30 30">
                                    <path d="M16 11h5l-9 10-9-10h5v-11h8v11zm1 11h-10v2h10v-2z" class="text-green-base fill-current" />
                                    </symbol>
                                    <use xlink:href="#download" width="30" height="30" y="4" x="4" />
                                </svg>
                            </a>
                        </div>

                        <div id="chart_div"></div>
                        
                        <div class="flex justify-start mt-6">
                            <h2 class="text-lg text-gray-900">Top 5 Performing Members</h2>
                        </div>

                        <div class="mt-3">
                            <div class="flex flex-col">
                                <div class="sm:overflow-x-auto md:overflow-x-hidden">
                                    <div class="align-middle inline-block min-w-full overflow-hidden">
                                        <table class="min-w-full">
                                        <thead>
                                            <tr class="sm:border-gray-200 border-b-2">
                                            <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                                Team Member
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
                                            @foreach ($trackingInformation as $row)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 md:border-b md:border-gray-200">
                                                {{{ $row['team_member'] }}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                                {{{ $row['doors'] }}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                                {{{ $row['hours'] }}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                                {{{ $row['sets'] }}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                                {{{ $row['sits'] }}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                                {{{ $row['set_closes'] }}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                                {{{ $row['closes'] }}}
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
                </div>
            </div>
        </div>
    </div>
</x-app.auth>

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

            initDate() {
                let today = new Date();
                this.month = today.getMonth();
                this.year = today.getFullYear();
                this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
            },

            isToday(date) {
                const today = new Date();
                const d = new Date(this.year, this.month, date);

                return today.toDateString() === d.toDateString() ? true : false;
            },

            getDateValue(date) {
                let selectedDate = new Date(this.year, this.month, date);
                this.datepickerValue = selectedDate.toDateString();

                this.$refs.date.value = selectedDate.getFullYear() +"-"+ ('0'+ selectedDate.getMonth()).slice(-2) +"-"+ ('0' + selectedDate.getDate()).slice(-2);

                console.log(this.$refs.date.value);

                this.showDatepicker = true;
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