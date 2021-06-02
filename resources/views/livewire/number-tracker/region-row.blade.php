<div>
    <div class="grid grid-cols-12 cursor-pointer hover:bg-gray-100 @if($itsOpen)) bg-gray-200 @endif"
            wire:click="collapseRegion()" >
        <x-table-accordion.default-td-arrow class="col-span-4" :open="$region['itsOpen']">
            <div class="flex" x-data wire:key="region-{{$region->id}}">
                <input
                    class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                    type="checkbox"
                    wire:change="selectRegion"
                    wire:model="itsSelected"
                    wire:click.stop=""
                >
            </div>
            <label>{{$region['name']}}</label>
        </x-table-accordion.default-td-arrow>
        <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif">
                {{$this->sumOf('closes') }}
            </div>
        </x-table-accordion.td>
    </div>
    @if ($itsOpen)
        @forelse ($region->offices as $office)
            <livewire:number-tracker.office-row :office="$office" key="office-{{$office->id}}"/>
        @empty
            <div class="table-row">
                <x-table-accordion.td class=" pl-14">
                    Empty
                </x-table-accordion.td>
            </div>
        @endforelse
    @endif
</div>
