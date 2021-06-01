<div wire:key="{{now()}}">
    <div class="grid grid-cols-12 cursor-pointer hover:bg-gray-100 @if($itsOpen)) bg-gray-200 @endif"
            wire:click="collapseRegion()" >
        <x-table-accordion.default-td-arrow class="col-span-4" :open="$region['itsOpen']">
            <div class="flex" x-data wire:key="region-{{$region->id}}">
                <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                        wire:model="itsSelected" type="checkbox" wire:click.stop="">
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
                {{$this->sumOf('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5"
                wire:loading wire:target="initRegionsData">
            </x-svg.spinner>
            <div class="@if($region['deleted_at'] != null) text-red-500 @endif" wire:loading.remove wire:target="initRegionsData">
                {{$this->sumOf('closes') }}
            </div>
        </x-table-accordion.td>
    </div>
    @if ($itsOpen)
        @forelse ($region->offices as $office)
        {{-- Neasted here a component to offices --}}
            <p>Future Component</p>
        @empty
            <div class="table-row">
                <x-table-accordion.td class=" pl-14">
                    Empty
                </x-table-accordion.td>
            </div>
        @endforelse
    @endif
</div>
