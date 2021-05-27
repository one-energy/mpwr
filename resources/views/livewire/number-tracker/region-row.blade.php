<div>
    <div class="table-row cursor-pointer hover:bg-gray-100 @if($region['itsOpen']) bg-gray-200 @endif"
            wire:click.stop="collapseRegion({{$region['id']}})" >
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
    @if($region['isOpen'])
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
        @endforelse --}}
    @endif
</div>
