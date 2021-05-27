<div>

    <livewire:number-tracker.numbers-ratios />

    <livewire:number-tracker.total-overview key="total-overview" />

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

                                    <livewire:number-tracker.region-row :region="$region"/>
                                    
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
