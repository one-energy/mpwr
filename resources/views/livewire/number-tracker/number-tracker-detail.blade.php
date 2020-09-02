<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="md:flex">
            <div class="py-5 md:w-1/3 overflow-hidden">
                <div class="flex justify-start">
                    <h3 class="text-lg text-gray-900">Number Tracker</h3>
                </div>

                <ul class="flex border-b mt-3">
                    <li class="-mb-px mr-4">
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
                            class="relative hidden top-2 w-6" 
                            wire:loading.class.remove="hidden">
                        </x-svg.spinner>
                    </li>
                </ul>

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
                                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex" >	
                                                <div style="width: 14.28%" class="px-1 mb-1" wire:click="setDate">
                                                    <div
                                                        @click="getDateValue(date); setCurrentDate(date); @this.set('date', getDateValue(date)); @this.call('setDate')"
                                                        x-text="date"
                                                        class="cursor-pointer text-center text-sm rounded-full leading-loose transition ease-in-out duration-100"
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

                <div class="border-gray-200 border-2 p-4 rounded-lg">
                    <div class="flex justify-between">
                        <span>
                            Filters
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <symbol id="filter" viewBox="0 0 24 24">
                                <path d="M19.479 2l-7.479 12.543v5.924l-1-.6v-5.324l-7.479-12.543h15.958zm3.521-2h-23l9 15.094v5.906l5 3v-8.906l9-15.094z" class="text-gray-700 fill-current"/>
                            </symbol>
                            <use xlink:href="#filter" width="15" height="15" y="4" x="4" />
                        </svg>
                    </div>
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
                    
                    <!-- Filter -->
                    <section class="mt-6">
                        <article>
                            <div class="border-b border-gray-200">
                                <header class="flex justify-between items-center py-2 cursor-pointer select-none">
                                    <span class="text-gray-70 font-thin text-sm">
                                        Region
                                    </span>
                                    <div class="ml-4">
                                        <x-svg.plus class="text-gray-300"></x-svg.plus>
                                    </div>
                                </header>
                            </div>
                        </article>
                        <article>
                            <div class="border-b bg-grey-lightest border-gray-200">
                                <header class="flex justify-between items-center py-2 cursor-pointer select-none">
                                    <span class="text-gray-700 font-thin text-sm">
                                        Member Region
                                    </span>
                                    <div class="flex">
                                        <div class="rounded-full border border-gray-200 w-4 h-4 flex items-center justify-center bg-gray-200 text-gray-700 text-xs">
                                            1
                                        </div>
                                        <div class="ml-4">
                                            <x-svg.plus class="text-gray-300"></x-svg.plus>
                                        </div>
                                    </div>
                                </header>
                                <div>
                                    <div class="pl-2 pb-5 text-sm text-grey-darkest">
                                        <ul class="pl-2">
                                            <li class="pb-2">
                                                Closer
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <div class="flex justify-between">
                            <div class="flex mt-12">
                                <span class="text-sm">
                                    Active Filters
                                </span>
                                <div class="ml-6 mt-1 rounded-full border border-gray-200 w-4 h-4 flex items-center justify-center bg-gray-200 text-gray-700 text-xs">
                                    1
                                </div>
                            </div>
                            <div class="mt-12">
                                <a href="#" class="text-xs text-gray-600">
                                    Clear Filters
                                </a>
                            </div>
                        </div>
                        <div class="mt-2 border-t border-gray-200">
                            <div class="mt-2">
                                <span class="rounded-full text-xs border border-gray-700 px-2">
                                    Closer
                                </span>
                            </div>
                        </div>
                    </section>
                    
                    <div class="mt-6">
                        <button type="submit" class="inline-flex w-full justify-center py-2 px-4 border-2 border-gray-700 text-sm leading-5 font-medium rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Apply Filters
                        </button>
                    </div>
                </div>
                <div class="mt-6">
                    @if(user()->role != 'Setter' && user()->role != 'Sales Rep')
                        <x-button :href="route('number-tracking.create')" color="green" class="inline-flex w-full">
                            Update Numbers
                        </x-button>
                    @endif
                </div>
            </div>
            
            <div class="px-4 py-5 sm:p-6 md:w-2/3">
                <div class="justify-center w-full">
                    <div class="flex justify-between md:mt-12 mt-6">
                        <div class="w-full grid md:grid-cols-4 grid-cols-2 md:col-gap-4 col-gap-1 row-gap-2">
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">D.P.S</div>
                                    <div class="text-xl text-green-base font-bold">
                                        {{$numbersTracked->sum('sets') ? number_format($numbersTracked->sum('doors')/$numbersTracked->sum('sets'), 2) : 0}}
                                    </div>
                                </div>
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">H.P. Set</div>
                                    <div class="text-xl text-green-base font-bold">
                                        {{$numbersTracked->sum('sets') ? number_format($numbersTracked->sum('hours')/$numbersTracked->sum('sets'), 2) : 0}}
                                    </div>
                                </div>
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">Sit Ratio</div>
                                    <div class="text-xl text-green-base font-bold">
                                        {{$numbersTracked->sum('sets') ? number_format($numbersTracked->sum('sits')/$numbersTracked->sum('sets'), 2) : 0}}
                                    </div>
                                </div>
                                <div class="col-span-1 bg-green-light rounded-lg p-3">
                                    <div class="text-xs text-green-base font-semibold uppercase">Close Ratio</div>
                                    <div class="text-xl text-green-base font-bold">
                                        {{$numbersTracked->sum('sets') ? number_format($numbersTracked->sum('closes')/$numbersTracked->sum('sets'), 2) : 0}}
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="flex justify-start mt-6">
                        <h2 class="text-lg text-gray-900">Total Overviews</h2>
                    </div>

                    <div class="flex justify-between mt-3">
                        <div class="w-full grid xl:grid-cols-6 grid-cols-3 md:col-gap-4 col-gap-1 row-gap-2">
                            <div class="col-span-1 border-2 
                                @if($filterBy == 'doors')
                                    border-green-base bg-green-light
                                @else
                                    border-gray-200
                                @endif
                                cursor-pointer
                                rounded-lg p-3"
                                wire:click="setFilterBy('doors')">
                                <div class="text-xs font-semibold uppercase">Doors</div>
                                <div class="text-xl font-bold">{{$numbersTracked->sum('doors')}}</div>
                                <div class="flex font-semibold text-xs text-green-base">
                                    @if($numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors') >= 0)
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-down>
                                    @endif
                                    <span class="@if($numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors') >= 0) 
                                                    text-green-base 
                                                @else 
                                                    text-red-600
                                                @endif">
                                        {{$numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-1 border-2 @if($filterBy == 'hours')
                                    border-green-base bg-green-light
                                @else
                                    border-gray-200
                                @endif
                                cursor-pointer
                                rounded-lg p-3"
                                wire:click="setFilterBy('hours')">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Hours</div>
                                <div class="text-xl text-gray-900 font-bold">{{$numbersTracked->sum('hours')}}</div>
                                <div class="flex font-semibold text-xs text-green-base">
                                    @if($numbersTracked->sum('hours') - $numbersTrackedLast->sum('hours') >= 0)
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-down>
                                    @endif
                                    <span class="@if($numbersTracked->sum('hours') - $numbersTrackedLast->sum('hours') >= 0) 
                                                    text-green-base 
                                                @else 
                                                    text-red-600
                                                @endif">
                                        {{$numbersTracked->sum('hours') - $numbersTrackedLast->sum('hours')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-1 border-2 @if($filterBy == 'sets')
                                    border-green-base bg-green-light
                                @else
                                    border-gray-200
                                @endif
                                cursor-pointer
                                rounded-lg p-3"
                                wire:click="setFilterBy('sets')">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Sets</div>
                                <div class="text-xl text-gray-900 font-bold">{{$numbersTracked->sum('sets')}}</div>
                                <div class="flex font-semibold text-xs text-green-base">
                                    @if($numbersTracked->sum('sets') - $numbersTrackedLast->sum('sets') >= 0)
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-down>
                                    @endif
                                    <span class="@if($numbersTracked->sum('sets') - $numbersTrackedLast->sum('sets') >= 0) 
                                                    text-green-base 
                                                @else 
                                                    text-red-600
                                                @endif">
                                        {{$numbersTracked->sum('sets') - $numbersTrackedLast->sum('sets')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-1 border-2 @if($filterBy == 'sits')
                                    border-green-base bg-green-light
                                @else
                                    border-gray-200
                                @endif
                                cursor-pointer
                                rounded-lg p-3"
                                wire:click="setFilterBy('sits')">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Sits</div>
                                <div class="text-xl text-gray-900 font-bold">{{$numbersTracked->sum('sits')}}</div>
                                <div class="flex font-semibold text-xs text-green-base">
                                    @if($numbersTracked->sum('sits') - $numbersTrackedLast->sum('sits') >= 0)
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-down>
                                    @endif
                                    <span class="@if($numbersTracked->sum('sits') - $numbersTrackedLast->sum('sits') >= 0) 
                                                    text-green-base 
                                                @else 
                                                    text-red-600
                                                @endif">
                                        {{$numbersTracked->sum('sits') - $numbersTrackedLast->sum('sits')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-1 border-2 @if($filterBy == 'set_closes')
                                    border-green-base bg-green-light
                                @else
                                    border-gray-200
                                @endif
                                cursor-pointer
                                rounded-lg p-3"
                                wire:click="setFilterBy('set_closes')">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Set closes</div>
                                <div class="text-xl text-gray-900 font-bold">{{$numbersTracked->sum('set_closes')}}</div>
                                <div class="flex font-semibold text-xs text-green-base">
                                    @if($numbersTracked->sum('set_closes') - $numbersTrackedLast->sum('set_closes') >= 0)
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-down>
                                    @endif
                                    <span class="@if($numbersTracked->sum('set_closes') - $numbersTrackedLast->sum('set_closes') >= 0) 
                                                    text-green-base 
                                                @else 
                                                    text-red-600
                                                @endif">
                                        {{$numbersTracked->sum('set_closes') - $numbersTrackedLast->sum('set_closes')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-1 border-2 @if($filterBy == 'closes')
                                    border-green-base bg-green-light
                                @else
                                    border-gray-200
                                @endif
                                cursor-pointer
                                rounded-lg p-3"
                                wire:click="setFilterBy('closes')">
                                <div class="text-xs text-gray-900 font-semibold uppercase">Closes</div>
                                <div class="text-xl text-gray-900 font-bold">{{$numbersTracked->sum('closes')}}</div>
                                <div class="flex font-semibold text-xs text-green-base">
                                    @if($numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors') >= 0)
                                        <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                    @else
                                        <x-svg.arrow-down class="text-red-600"></x-svg.arrow-down>
                                    @endif
                                    <span class="@if($numbersTracked->sum('doors') - $numbersTrackedLast->sum('doors') >= 0) 
                                                    text-green-base 
                                                @else 
                                                    text-red-600
                                                @endif">
                                        {{$numbersTracked->sum('closes') - $numbersTrackedLast->sum('closes')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Chart -->
                    <div class="flex justify-between mt-6 w-full">
                        <div>
                            <div class="font-bold text-lg">
                                {{$graficValue}}
                            </div>
                            <div class="flex font-semibold text-xs 
                                @if($graficValueLast > $graficValue)
                                    text-red-600
                                @else
                                    text-green-base
                                @endif">
                                @if($graficValueLast > $graficValue)
                                    <x-svg.arrow-down class="text-red-600"></x-svg.arrow-up>                                                                
                                @else
                                    <x-svg.arrow-up class="text-green-base"></x-svg.arrow-up>
                                @endif
                                <span>
                                    {{$graficValue - $graficValueLast}}
                                    @if($numbersTrackedLast->sum('closes') != 0 )
                                        ({{number_format((($graficValue - $graficValueLast)/$graficValueLast)*100, 2)}}%)
                                    @else
                                        (0%)
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a href="#">
                            <x-svg.panel></x-svg.panel>
                        </a>
                    </div>
                    <div class="flex md:justify-between w-full" id="chart_div"></div>
                    
                    <div class="flex justify-start mt-6">
                        <h2 class="text-lg text-gray-900">Top 5 Performing Members</h2>
                    </div>
                    <div class="flex justify-center w-full">
                        <x-svg.spinner 
                            color="#9fa6b2" 
                            class="self-center hidden w-20 mt-3" 
                            wire:loading.class.remove="hidden">
                        </x-svg.spinner>            
                                                                    
                        <div class="mt-6 w-full"wire:loading.remove>
                            <div class="flex flex-col">
                                <div class="overflow-x-auto">
                                    <div class="align-middle inline-block min-w-full overflow-hidden">
                                        @if(count($numbersTracked))
                                            <x-table>
                                                <x-slot name="header">
                                                    <x-table.th-tr>
                                                        <x-table.th by="region_number">
                                                            @lang('Region Member')
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
                                                    @foreach($numbersTracked->take(5) as $row)
                                                        <x-table.tr :loop="$loop">
                                                            <x-table.td>{{ $row['first_name'] . ' ' .  $row['last_name']}}</x-table.td>
                                                            <x-table.td>{{ $row['doors'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['hours'] ?? 0 }}</x-table.td>
                                                            <x-table.td>{{ $row['sets'] ?? 0 }}</x-table.td>
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
  google.charts.load("current", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable
        ([['Week', 'Sales', {'type': 'string', 'role': 'style'}],
          [1, 3, null],
          [2, 24.5, null],
          [3, 2, null],
          [4, 3, null],
          [5, 14.5, null],
          [6, 6.5, null],
          [7, 9, null],
          [8, 12, null],
          [9, 55, null],
          [10, 34, null],
          [11, 46, 'point { size: 3; shape-type: circle; fill-color: #46A049; }']
    ]);
    var options = {
      legend: 'none',
      colors: ['#46A049'],
      pointSize: 1,
      vAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
      hAxis: { gridlines: { count: 0 }, textPosition: 'none' },
      chartArea:{left:0,top:0,width:"99%",height:"100%"}
    };
    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>
