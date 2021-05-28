<div class="table">
    <div class="table-row-group">
        <div class="table-row cursor-pointer hover:bg-gray-100 @if($region['itsOpen']) bg-gray-200 @endif"
            wire:click="collapseRegion()" >
        <x-table-accordion.default-td-arrow class="table-cell" :open="$region['itsOpen']">
            <div class="flex" x-data wire:loading.remove wire:key="{{$region['id']}}">
                <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                        wire:model="itsOpenRegions.{{$region['id']}}.selected"
                        type="checkbox" x-on:change="$wire.selectRegion({{$region['id']}})" wire:click.stop="" >
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
                {{$this->sumOf('daily_numbers_sum_hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('daily_numbers_sum_closes') }}
            </div>
        </x-table-accordion.td>
    </div>
    @if($itsOpen)
        @foreach ($region->offices as $office)
            <div class="table-row cursor-pointer hover:bg-gray-100 @if(true) bg-gray-100 @endif">
                <x-table-accordion.child-td-arrow class="table-cell" >
                    <div class="flex">
                        <div wire:loading.remove>
                            <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                    type="checkbox">
                        </div>
                        <div class="flex items-center mr-2 w-6 h-6" wire:loading>
                            <x-svg.spinner
                                color="#9fa6b2"
                                class="self-center ">
                            </x-svg.spinner>
                        </div>
                        <label>sdasdas sds  sdasda sdsd s sdsad</label>
                    </div>
                </x-table-accordion.child-td-arrow>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5">
                    </x-svg.spinner>
                    <div>
                    2312
                    </div>
                </x-table-accordion.td>
            </div>
        @endforeach
        {{-- @forelse($region['sortedOffices'] as $officeIndex => $office)
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
                        {{$this->sumOfficeNumberTracker($office, 'hours_worked') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'doors') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'hours_knocked') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'sets') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'sats') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'set_closes') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'closer_sits') }}
                    </div>
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                    <x-svg.spinner
                        color="#9fa6b2"
                        class="self-center hidden w-5"
                        wire:loading wire:target="initRegionsData">
                    </x-svg.spinner>
                    <div class="@if($office['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                        {{$this->sumOfficeNumberTracker($office, 'closes') }}
                    </div>
                </x-table-accordion.td>
            </div>
        @empty
            <div class="table-row">
                <x-table-accordion.td class="table-cell pl-14">
                    Empty
                </x-table-accordion.td>
            </div>
        @endforelse --}}
    @endif
    </div>
    
</div>
