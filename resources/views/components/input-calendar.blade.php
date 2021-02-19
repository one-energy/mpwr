@props(['label', 'name', 'value', 'disabledToUser', 'disabled'])

@php
    $class = 'form-input block w-full pr-10 sm:text-sm sm:leading-5';
    if( $errors->has($name) ) {
        $class .= 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red';
    }
    $disabledToUser = $disabledToUser ?? null;
    $disabled = $disabled ?? false;
@endphp

<div {{ $attributes }} x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
    <label for="{{ $name }}" class="block text-sm font-medium leading-5 text-gray-700">{{ $label }}</label>
    <div class="mt-1 relative rounded-md shadow-sm">
        <input type="datetime" {{ $attributes->except('class')->merge(['class' => $class]) }} x-model="datepickerValue"
            @click="showDatepicker = !showDatepicker" @keydown.escape="showDatepicker = false"
            @if(($disabledToUser && user()->role == $disabledToUser) || $disabled) disabled @endif
            readonly>
        @error($name)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
        @enderror
        <div class="absolute top-0 right-0 px-3 py-2">
            <svg class="h-6 w-6 text-gray-400"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

        <div
            class="bg-white mt-12 rounded-lg shadow p-4 absolute top-0 left-0 z-10"
            x-show.transition="showDatepicker"
            @click.away="showDatepicker = false">

            <div class="flex justify-between items-center mb-2">
                <div>
                    <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                    <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                </div>
                <div>
                    <button
                        type="button"
                        class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full"
                        @click="
                            month == 0 ? year-- : year = year;
                            month > 0 ? month-- : month = 11;
                            getNoOfDays()">
                        <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full"
                        @click="
                            month == 11 ? year++ : year = year;
                            month < 11 ? month++ : month = 0;
                            getNoOfDays()
                        ">
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
                            class="text-gray-800 font-medium text-center text-xs">
                        </div>
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
                            :class="{'bg-green-base text-white': isSelectedDate(date) == true, 'text-gray-700 hover:bg-blue-200': isSelectedDate(date) == false }"
                        ></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
<script>
    const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    function app() {
        return {
            showDatepicker: false,
            datepickerValue: @entangle($attributes->wire('model')),
            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

            initDate() {
                let day = this.datepickerValue ? new Date(this.datepickerValue) : new Date();
                this.month = day.getMonth();
                this.year = day.getFullYear();
                this.datepickerValue = new Date(this.year, this.month, day.getDate()).toDateString();
            },

            isToday(date) {
                const today = new Date();
                const d = new Date(this.year, this.month, date);
                return today.toDateString() === d.toDateString() ? true : false;
            },

            isSelectedDate(date) {
                const d = new Date(this.year, this.month, date);
                return this.datepickerValue === d.toDateString() ? true : false;
            },

            getDateValue(date) {
                let selectedDate = new Date(this.year, this.month, date);
                this.datepickerValue = selectedDate.toDateString();
                this.showDatepicker = false;
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
