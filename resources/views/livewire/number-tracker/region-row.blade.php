<div>
    <div
        class="grid cursor-pointer group"
        style="grid-template-columns: repeat(4, minmax(80px, 80px)) repeat(8, minmax(153px, 153px))"
        wire:click="collapseRegion()"
    >
        <x-table-accordion.default-td-arrow class="group-hover:bg-gray-100 col-span-4 {{ $itsOpen ? 'bg-gray-200' : '' }}" :open="$region['itsOpen']">
            <div class="flex" x-data wire:key="region-{{$region->id}}" wire:loading.remove>
                <input
                    class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                    type="checkbox"
                    wire:change="selectRegion"
                    wire:model="itsSelected"
                    wire:click.stop=""
                >
            </div>
            <x-svg.spinner wire:loading color="#9fa6b2" class="self-center hidden w-5 mr-2" />
            <label>{{$region['name']}}</label>
        </x-table-accordion.default-td-arrow>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="hours_worked" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="doors" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="hours_knocked" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="sets" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="sats" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="set_closes" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="closer_sits" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-200' : '' }}" by="closes" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($region->trashed()) text-red-500 @endif">
                {{$this->sumOf('closes') }}
            </div>
        </x-table-accordion.td>
    </div>
    @if ($itsOpen)
        @forelse ($offices as $office)
            <livewire:number-tracker.office-row
                :officeId="$office->id"
                :selected="$itsSelected"
                :period="$period"
                :selectedDate="$selectedDate"
                :withTrashed="$withTrashed"
                key="office-{{$office->id}}"
            />
        @empty
            <div class="table-row">
                <x-table-accordion.td class=" pl-14">Empty</x-table-accordion.td>
            </div>
        @endforelse
    @endif
</div>
