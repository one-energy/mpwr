<div>
    <div class="flex justify-between mt-6 md:mt-12">
        <div class="grid w-full grid-cols-2 row-gap-2 col-gap-1 md:grid-cols-4 md:col-gap-4">
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">D.P.S</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->getDps()}}
                </div>
            </div>
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">H.P. Set</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->getHps()}}
                </div>
            </div>
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">Sit Ratio</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->getSitRatio()}}
                </div>
            </div>
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">Close Ratio</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->getCloseRatio()}}
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mt-6">
        <h2 class="text-lg text-gray-900">Total Overviews</h2>

        @if (user()->notHaveRoles(['Setter', 'Sales Rep']))
            <a
                href="{{ route('number-tracking.spreadsheet') }}"
                class="py-2 px-3 focus:outline-none rounded shadow-md text-white bg-green-base"
                title="Spreadsheet Page"
            >
                <x-svg.spreadsheet class="w-5 h-5 text-white fill-current" />
            </a>
        @endif
    </div>

    <div class="mt-3 overflow-auto flex flex-row space-x-4 p-3">
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1">
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold uppercase">Doors</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold">{{$this->getNumberTrackerSumOf('doors')}}</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('doors') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('doors') >= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('doors')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">Hours</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold text-gray-900">{{$this->getNumberTrackerSumOf('hours')}}</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('hours') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('hours') >= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('hours')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">Sets</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold text-gray-900">{{$this->getNumberTrackerSumOf('sets')}}</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('sets') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('sets')>= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('sets')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>

        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1">
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold uppercase">
                Hours Worked
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold">
                {{$this->getNumberTrackerSumOf('hoursWorked')}}
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('hoursWorked') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('hoursWorked') >= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('hoursWorked')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">
                Hours Knocked
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold text-gray-900">
                {{$this->getNumberTrackerSumOf('hoursKnocked')}}
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('hoursKnocked') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('hoursKnocked') >= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('hoursKnocked')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">
                Sats
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold text-gray-900">
                {{$this->getNumberTrackerSumOf('sats')}}
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('sats') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('sats')>= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('sats')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">
                Closer Sits
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-xl font-bold text-gray-900">
                {{$this->getNumberTrackerSumOf('closerSits')}}
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="flex text-xs font-semibold text-green-base">
                @if($this->getNumberTrackerDifferenceToLasNumbersOf('closerSits') >= 0)
                    <x-svg.arrow-up class="text-green-base"/>
                @else
                    <x-svg.arrow-down class="text-red-600"/>
                @endif
                <span class="
                    text-base
                    @if($this->getNumberTrackerDifferenceToLasNumbersOf('closerSits')>= 0)
                        text-green-base
                    @else
                        text-red-600
                    @endif
                ">
                        {{$this->getNumberTrackerDifferenceToLasNumbersOf('closerSits')}}
                    </span>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>

        <div style="min-width: fit-content; flex: 0 0 auto" class="w-56 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">Sits</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="grid grid-cols-4 gap-1">
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
                        text-base
                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('setSits') >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('setSits')}}
                        </span>
                </div>
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="grid grid-cols-4 gap-1">
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
                        text-base
                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('sits') >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('sits')}}
                        </span>
                </div>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
        <div style="min-width: fit-content; flex: 0 0 auto" class="w-56 border-2 border-gray-200 rounded-md p-3 space-y-1" >
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="text-base font-semibold text-gray-900 uppercase">Closes</div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="grid grid-cols-4 gap-1">
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
                        text-base
                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('setCloses') >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('setCloses')}}
                        </span>
                </div>
            </div>
            <div wire:loading.remove wire:target="selectRegion, selectOffice, selectDailyNumberUser" class="grid grid-cols-4 gap-1">
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
                        text-base
                        @if($this->getNumberTrackerDifferenceToLasNumbersOf('closes') >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                            {{$this->getNumberTrackerDifferenceToLasNumbersOf('closes')}}
                        </span>
                </div>
            </div>
            <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectDailyNumberUser"/>
        </div>
    </div>

    @if (user()->hasAnyRole(['Admin', 'Owner']))
        <div class="flex justify-end mt-4">
            <x-select name="departments" label="Departments" wire:model="selectedDepartment">
                @foreach($this->departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </x-select>
        </div>
    @endif

    <div class="justify-end mt-5">
        <x-toggle wire:click="initRegionsData" wire:model="deleteds" class="items-end" label="Deleted"/>
    </div>

    <div class="flex justify-center w-full">
        <x-svg.spinner
            color="#9fa6b2"
            class="self-center hidden w-20 mt-3"
            wire:loading.class.remove="hidden" wire:target="setDate, setPeriod, addFilter, removeFilter">
        </x-svg.spinner>

        <div class="w-full mt-6" wire:loading.remove wire:target="setDate, setPeriod, addFilter, removeFilter">
            <div class="flex flex-col">
                <div class="inline-block min-w-full align-middle">
                    @if(count($itsOpenRegions))
                        <x-table-accordion>
                            <x-slot name="header">
                                <x-table-accordion.th-searchable class="sticky top-0 bg-white table-cell" by="deparmtent" :sortedBy="$sortBy" :direction="$sortDirection"></x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="hours_worked" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Hours Worked')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="doors" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Doors')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="hours_knocked" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Hours Knocked')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="sets" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Sets')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="sats" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Sats')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="set_closes" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Set Closes')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="closer_sits" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Closer Sits')
                                </x-table-accordion.th-searchable>
                                <x-table-accordion.th-searchable wire:click="initRegionsData" class="sticky top-0 bg-white table-cell" by="closes" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Closes')
                                </x-table-accordion.th-searchable>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($itsOpenRegions as $regionIndex => $region)
                                    <div class="table-row cursor-pointer hover:bg-gray-100 @if($region['itsOpen']) bg-gray-200 @endif"
                                         wire:click.stop="collapseRegion({{$regionIndex}})" >
                                        <x-table-accordion.default-td-arrow class="table-cell" :open="$region['itsOpen']">
                                            <div class="flex" x-data wire:loading.remove wire:key="{{$regionIndex}}">
                                                <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                                       wire:model="itsOpenRegions.{{$regionIndex}}.selected"
                                                       type="checkbox" x-on:change="$wire.selectRegion({{$regionIndex}})" wire:click.stop="" >
                                            </div>
                                            <div class="flex items-center mr-2 w-6 h-6" wire:loading>
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center ">
                                                </x-svg.spinner>
                                            </div>
                                            <label>{{$region['name']}}</label>
                                        </x-table-accordion.default-td-arrow>
                                        <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'hours_worked')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'doors')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'hours_knocked')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'sets')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'sats')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'set_closes')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'closer_sits')) }}
                                            </div>
                                        </x-table-accordion.td>
                                        <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                            <x-svg.spinner
                                                color="#9fa6b2"
                                                class="self-center hidden w-5"
                                                wire:loading wire:target="initRegionsData">
                                            </x-svg.spinner>
                                            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                {{ $this->parseNumber($this->sumRegionNumberTracker($region, 'closes')) }}
                                            </div>
                                        </x-table-accordion.td>
                                    </div>
                                    @if($region['itsOpen'])
                                        @forelse($region['sortedOffices'] as $officeIndex => $office)
                                            <div class="table-row cursor-pointer hover:bg-gray-100 @if($office['itsOpen']) bg-gray-100 @endif"
                                                 wire:click.stop="collapseOffice({{$regionIndex}}, {{$officeIndex}})" wire:key="{{$regionIndex}}-{{$officeIndex}}">
                                                <x-table-accordion.child-td-arrow class="table-cell" :open="$office['itsOpen']">
                                                    <div class="flex">
                                                        <div wire:loading.remove>
                                                            <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                                                   type="checkbox" wire:change="selectOffice({{$regionIndex}}, {{$officeIndex}})" wire:click.stop=""
                                                                   wire:model="itsOpenRegions.{{$regionIndex}}.sortedOffices.{{$officeIndex}}.selected">
                                                        </div>
                                                        <div class="flex items-center mr-2 w-6 h-6" wire:loading>
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center ">
                                                            </x-svg.spinner>
                                                        </div>
                                                        <label>{{$office['name']}}</label>
                                                    </div>
                                                </x-table-accordion.child-td-arrow>
                                                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'hours_worked')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'doors')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'hours_knocked')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'sets')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'sats')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'set_closes')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'closer_sits')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                                <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                                    <x-svg.spinner
                                                        color="#9fa6b2"
                                                        class="self-center hidden w-5"
                                                        wire:loading wire:target="initRegionsData">
                                                    </x-svg.spinner>
                                                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                        {{ $this->parseNumber($this->sumOfficeNumberTracker($office, 'closes')) }}
                                                    </div>
                                                </x-table-accordion.td>
                                            </div>
                                            @if($office['itsOpen'])
                                                @forelse($office['sortedDailyNumbers'] as $dailyNumberIndex => $dailyNumber)
                                                    <div class="table-row hover:bg-gray-100" wire:key="{{$regionIndex}}-{{$officeIndex}}-{{$dailyNumberIndex}}">
                                                        <x-table-accordion.td class="table-cell pl-28">
                                                            <div class="flex items-center" x-data >
                                                                <div class="flex items-center" wire:loading.remove>
                                                                    <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                                                           wire:model="itsOpenRegions.{{$regionIndex}}.sortedOffices.{{$officeIndex}}.sortedDailyNumbers.{{$dailyNumberIndex}}.selected"
                                                                           type="checkbox" x-on:change="$wire.selectDailyNumberUser({{$regionIndex}}, {{$officeIndex}}, {{$dailyNumberIndex}})" wire:click.stop="">
                                                                </div>
                                                                <div class="flex items-center mr-2 w-6 h-6" wire:loading>
                                                                    <x-svg.spinner
                                                                        color="#9fa6b2"
                                                                        class="self-center ">
                                                                    </x-svg.spinner>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    @if ($dailyNumber['user']['deleted_at'] != null)
                                                                        <x-icon class="mr-2 w-6 h-6" icon="user-blocked"/>
                                                                    @endif
                                                                    <label>{{$dailyNumber['user']['full_name']}}</label>
                                                                </div>
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['hours_worked']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['doors']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['hours_knocked']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['sets']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['sats']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['set_closes']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['closer_sits']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                        <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                                            <x-svg.spinner
                                                                color="#9fa6b2"
                                                                class="self-center hidden w-5"
                                                                wire:loading wire:target="initRegionsData">
                                                            </x-svg.spinner>
                                                            <div class="@if($dailyNumber['deleted_at'] != null || $dailyNumber['user']['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                                {{ $this->parseNumber($dailyNumber['closes']) }}
                                                            </div>
                                                        </x-table-accordion.td>
                                                    </div>
                                                @empty
                                                    <div class="table-row">
                                                        <x-table-accordion.td class="table-cell pl-28">
                                                            Empty
                                                        </x-table-accordion.td>
                                                    </div>
                                                @endforelse
                                            @endif
                                            @if (count($office['sortedDailyNumbers']) && $office['itsOpen'])
                                                <div class="table-row hover:bg-gray-100" x-data>
                                                    <x-table-accordion.td class="table-cell" style="padding-left: 5.8rem;">
                                                        <div class="flex">
                                                            <div class="flex items-center" wire:loading.remove>
                                                                <input
                                                                    class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                                                    type="checkbox"
                                                                    wire:model="itsOpenRegions.{{$regionIndex}}.sortedOffices.{{$officeIndex}}.totalSelected"
                                                                    x-on:change="$wire.toggleOffices({{ $regionIndex }}, {{ $officeIndex }})"
                                                                >
                                                            </div>
                                                            <span class="font-bold">Office Total</span>
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'hours_worked')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'doors')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'hours_knocked')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'sets')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'sats')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'set_closes')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'closer_sits')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                                                            {{ $this->parseNumber($this->sumBy($office['sortedDailyNumbers'], 'closes')) }}
                                                        </div>
                                                    </x-table-accordion.td>
                                                </div>
                                            @endif
                                        @empty
                                            <div class="table-row">
                                                <x-table-accordion.td class="table-cell pl-14">
                                                    Empty
                                                </x-table-accordion.td>
                                            </div>
                                        @endforelse
                                    @endif
                                @endforeach
                            </x-slot>
                        </x-table-accordion>
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
