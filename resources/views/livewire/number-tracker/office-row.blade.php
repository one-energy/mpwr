<div class="cursor-pointer hover:bg-gray-100 @if($itsOpen) bg-gray-100 @endif">
    <div class="grid grid-cols-12" wire:click.stop="collapseOffice" >
        <x-table-accordion.child-td-arrow class="col-span-4" :open="$itsOpen">
            <div class="flex">
                <input
                    class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                    type="checkbox"
                    wire:change="selectOffice"
                    wire:model="selected"
                    wire:click.stop=""
                />
                <label>{{ $office->name }}</label>
            </div>
        </x-table-accordion.child-td-arrow>
        <x-table-accordion.td by="hours_worked" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="doors" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="hours_knocked" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="sets" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="sats" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="set_closes" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="closer_sits" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td by="closes" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('closes') }}
            </div>
        </x-table-accordion.td>
    </div>

    @if($itsOpen)
        @forelse ($this->usersDailyNumbers as $userDailyNumbers)
            <livewire:number-tracker.user-row :userDailyNumbers="$userDailyNumbers"  key="user-{{$userDailyNumbers[0]->user_id}}"/>
        @empty
            <div class="table-row">
                <x-table-accordion.td class=" pl-14">
                    Empty
                </x-table-accordion.td>
            </div>
        @endforelse
    @endif

    @if ($office->dailyNumbers->isNotEmpty() && $itsOpen)
        <div class="grid grid-cols-12 hover:bg-gray-100" x-data wire:key="officeTotal-{{$office->id}}">
            <x-table-accordion.td class="col-span-4" style="padding-left: 5.8rem;">
                <div class="flex">
                    <div class="flex items-center">
                        <input
                            class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                            type="checkbox"
                            wire:change="selectTotal"
                            wire:model="selectedTotal"
                            wire:click.stop="">
                    </div>
                    <span class="font-bold">Office Total</span>
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="hours_worked" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('hours_worked') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('doors') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="hours_knocked" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('hours_knocked') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('sets') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="sats" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('sats') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('set_closes') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="closer_sits" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('closer_sits') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('closes') }}
                </div>
            </x-table-accordion.td>
        </div>
    @endif
</div>
