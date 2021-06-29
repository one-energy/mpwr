<div class="cursor-pointer group">
    <div class="tracker-grid-container" wire:click.stop="collapseOffice">
        <x-table-accordion.child-td-arrow class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }}  col-span-4" :open="$itsOpen">
            <div class="flex" wire:loading.remove>
                <input
                    class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                    type="checkbox"
                    wire:change="selectOffice"
                    wire:model="selected"
                    wire:click.stop=""
                />
            </div>
            <x-svg.spinner wire:loading color="#9fa6b2" class="self-center hidden w-5 mr-2" />
            <label>{{ Str::limit($office->name, 20) }}</label>
        </x-table-accordion.child-td-arrow>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="hours_worked" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="doors" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="hours_knocked" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="sets" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="sats" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="set_closes" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="closer_sits" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="group-hover:bg-gray-100 {{ $itsOpen ? 'bg-gray-100' : '' }} " by="closes" sortedBy="$sortBy">
            <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5" />
            <div class="@if($office->trashed()) text-red-500 @endif">
                {{ $this->sumBy('closes') }}
            </div>
        </x-table-accordion.td>
    </div>

    @if($itsOpen)
        @forelse ($dailyNumbers as $userDailyNumbers)
            <livewire:number-tracker.user-row :userDailyNumbers="$userDailyNumbers" :isSelected="$selected" key="user-{{$userDailyNumbers[0]['user_id']}}"/>
        @empty
            <div class="table-row">
                <x-table-accordion.td class=" pl-14">
                    Empty
                </x-table-accordion.td>
            </div>
        @endforelse
    @endif

    @if ($office->dailyNumbers->isNotEmpty() && $itsOpen)
        <div class="grid parent-scope" x-data wire:key="officeTotal-{{$office->id}}" style="grid-template-columns: repeat(4, minmax(80px, 80px)) repeat(8, minmax(161px, 161px))">
            <x-table-accordion.td class="scope-child col-span-4" style="padding-left: 5.8rem;">
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
            <x-table-accordion.td class="scope-child" by="hours_worked" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('hours_worked') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="doors" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('doors') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="hours_knocked" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('hours_knocked') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="sets" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('sets') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="sats" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('sats') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="set_closes" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('set_closes') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="closer_sits" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('closer_sits') }}
                </div>
            </x-table-accordion.td>
            <x-table-accordion.td class="scope-child" by="closes" sortedBy="$sortBy">
                <x-svg.spinner color="#9fa6b2" class="self-center hidden w-5">
                </x-svg.spinner>
                <div class="@if($office->trashed()) text-red-500 @endif">
                    {{ $this->sumBy('closes') }}
                </div>
            </x-table-accordion.td>
        </div>
    @endif
</div>
