<div x-data="initAccordion()" x-init="[bootstrap()]">

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

    <div class="flex justify-start mt-6">
        <h2 class="text-lg text-gray-900">Total Overviews</h2>
    </div>

    <div class="flex justify-between mt-3">
        <div class="grid w-full grid-cols-6 row-gap-2 col-gap-1 xl:grid-cols-12 md:col-gap-4">
            <div class="col-span-2 xl:col-span-2 border-2 border-gray-200 rounded-md p-3 space-y-3">
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-base font-semibold uppercase">Doors</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-xl font-bold">{{$this->getNumberTrackerSumOf('doors')}}</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="flex text-xs font-semibold text-green-base">
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
                <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectUser"/>
            </div>
            <div class="col-span-2 xl:col-span-2 border-2 border-gray-200 rounded-md p-3 space-y-3" >
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-base font-semibold text-gray-900 uppercase">Hours</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-xl font-bold text-gray-900">{{$this->getNumberTrackerSumOf('hours')}}</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="flex text-xs font-semibold text-green-base">
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
                <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectUser"/>
            </div>
            <div class="col-span-2 xl:col-span-2 border-2 border-gray-200 rounded-md p-3 space-y-3" >
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-base font-semibold text-gray-900 uppercase">Sets</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-xl font-bold text-gray-900">{{$this->getNumberTrackerSumOf('sets')}}</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="flex text-xs font-semibold text-green-base">
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
                <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectUser"/>
            </div>
            <div class="col-span-3 xl:col-span-3 border-2 border-gray-200 rounded-md p-3 space-y-3" >
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-base font-semibold text-gray-900 uppercase">Sits</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="grid grid-cols-4 gap-1">
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
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="grid grid-cols-4 gap-1">
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
                <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectUser"/>
            </div>
            <div class="col-span-3 xl:col-span-3 border-2 border-gray-200 rounded-md p-3 space-y-3" >
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="text-base font-semibold text-gray-900 uppercase">Closes</div>
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="grid grid-cols-4 gap-1">
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
                <div wire:loading.remove wire:target="selectRegion, selectOffice, selectUser" class="grid grid-cols-4 gap-1">
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
                <x-card-pulse-loading wire:loading.flex wire:target="selectRegion, selectOffice, selectUser"/>
            </div>
        </div>
    </div>
    <div class="flex justify-center w-full">
        <x-svg.spinner
            color="#9fa6b2"
            class="self-center hidden w-20 mt-3"
            wire:loading.class.remove="hidden" wire:target="setDate, setPeriod, addFilter, removeFilter">
        </x-svg.spinner>

        <div class="w-full mt-6" wire:loading.remove wire:target="setDate, setPeriod, addFilter, removeFilter">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full overflow-hidden align-middle">
                        {{-- @dump($itsOpenRegions) --}}
                        @if(count($itsOpenRegions))
                            <x-table-accordion class="overflow-x-auto">
                                <x-slot name="header">
                                    <x-table-accordion.th-searchable class="table-cell" by="deparmtent" :sortedBy="$sortBy" :direction="$sortDirection"></x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="doors" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Doors')
                                    </x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="hours" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Hours')
                                    </x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="sets" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Sets')
                                    </x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="set_sits" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Set Sits')
                                    </x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="sits" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Sits')
                                    </x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="set_closes" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Set Closes')
                                    </x-table-accordion.th-searchable>
                                    <x-table-accordion.th-searchable wire:click="initRegionsData" class="table-cell" by="closes" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Closes')
                                    </x-table-accordion.th-searchable>
                                </x-slot>
                                <x-slot name="body">

                                    @foreach($itsOpenRegions as $regionIndex => $region)
                                        <div class="table-row cursor-pointer hover:bg-gray-100 @if($region['itsOpen']) bg-gray-200 @endif"
                                            wire:click.stop="collapseRegion({{$regionIndex}})" >
                                            <x-table-accordion.default-td-arrow class="table-cell" :open="$region['itsOpen']">
                                                <div class="flex" x-data wire:loading.remove wire:key="now()">
                                                    <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                                        type="checkbox" x-on:change="$wire.selectRegion({{$regionIndex}})" checked wire:click.stop="" >
                                                </div>
                                                <label>{{$region['name']}}</label>
                                            </x-table-accordion.td-arrow>
                                            <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'doors')}}
                                                </div>
                                            </x-table-accordion.td>
                                            <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'hours')}}
                                                </div>
                                            </x-table-accordion.td>
                                            <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'sets')}}
                                                </div>
                                            </x-table-accordion.td>
                                            <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'set_sits')}}
                                                </div>
                                            </x-table-accordion.td>
                                            <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'sits')}}
                                                </div>
                                            </x-table-accordion.td>
                                            <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'set_closes')}}
                                                </div>
                                            </x-table-accordion.td>
                                            <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                                <x-svg.spinner
                                                    color="#9fa6b2"
                                                    class="self-center hidden w-5"
                                                    wire:loading wire:target="initRegionsData">
                                                </x-svg.spinner>
                                                <div wire:loading.remove wire:target="initRegionsData">
                                                    {{$this->sumRegionNumberTracker($region, 'closes')}}
                                                </div>
                                            </x-table-accordion.td>
                                        </div>
                                        @if($region['itsOpen'])
                                            @forelse($region['sortedOffices'] as $officeIndex => $office)
                                                <div class="table-row cursor-pointer hover:bg-gray-100 @if($office['itsOpen']) bg-gray-100 @endif"
                                                    wire:click.stop="collapseOffice({{$regionIndex}}, {{$officeIndex}})">
                                                    <x-table-accordion.child-td-arrow class="table-cell" :open="$office['itsOpen']">
                                                        <div class="flex" x-data wire:loading.remove>
                                                            <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2" wire:key="now()"
                                                                type="checkbox" x-on:change="$wire.selectOffice({{$regionIndex}}, {{$officeIndex}})" checked wire:click.stop="">
                                                        </div>
                                                        <label>{{$office['name']}}</label>
                                                    </x-table-accordion.td-arrow>
                                                    <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'doors')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'hours')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'sets')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'set_sits')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'sits')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'set_closes')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                    <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                                        <x-svg.spinner
                                                            color="#9fa6b2"
                                                            class="self-center hidden w-5"
                                                            wire:loading wire:target="initRegionsData">
                                                        </x-svg.spinner>
                                                        <div wire:loading.remove wire:target="initRegionsData">
                                                            {{$this->sumOfficeNumberTracker($office, 'closes')}}
                                                        </div>
                                                    </x-table-accordion.td>
                                                </div>
                                                @if($office['itsOpen'])
                                                    @forelse($office['sortedUsers'] as $userIndex => $user)
                                                        <div class="table-row hover:bg-gray-100" >
                                                            <x-table-accordion.td class="table-cell pl-28">
                                                                <div class="flex" x-data >
                                                                    <div wire:key="now()" wire:loading.remove>
                                                                        @if ($user['deleted_at'] != null)
                                                                            <x-icon class="mr-2 w-auto" name="trash"/>
                                                                        @endif
                                                                        <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                                                            type="checkbox" x-on:change="$wire.selectUser({{$regionIndex}}, {{$officeIndex}}, {{$userIndex}})" checked wire:click.stop="">
                                                                    </div>
                                                                    <label>{{$user['full_name']}}</label>
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'doors')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'hours')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'sets')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'set_sits')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'sits')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'set_closes')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                            <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                                                <x-svg.spinner
                                                                    color="#9fa6b2"
                                                                    class="self-center hidden w-5"
                                                                    wire:loading wire:target="initRegionsData">
                                                                </x-svg.spinner>
                                                                <div wire:loading.remove wire:target="initRegionsData">
                                                                    {{$this->sumUserNumberTracker($user, 'closes')}}
                                                                </div>
                                                            </x-table-accordion.td>
                                                        </div>
                                                    @empty
                                                        <div class="table-row">
                                                            Empty
                                                        </div>
                                                    @endforelse
                                                @endif
                                            @empty
                                                Empty
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
</div>

@push('scripts')
    <script>
        function initAccordion() {
            return {
                bootstrap () {
                    window.livewire.emit('sumTotalNumbers', @json($totals))
                }
            }
        }
    </script>
@endpush
