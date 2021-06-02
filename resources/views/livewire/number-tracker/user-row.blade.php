<div wire:key="{{now()}}">
    <div class="grid grid-cols-12 cursor-pointer bg-white hover:bg-gray-100"
            wire:click="collapseRegion()" >
        <x-table-accordion.td class="col-span-4">
            <div class="flex ml-28">
                <div class="flex" x-data wire:key="dailyeEntry-{{now()}}">
                    <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                            wire:model="itsSelected" type="checkbox" wire:click.stop="">
                </div>
                <label>{{$usersDailyNumbers[0]->user?->fullName ?? "Removed"}}</label>
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif" class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif" class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td>
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($usersDailyNumbers[0]->deleted_at != null) text-red-500 @endif">
                {{$usersDailyNumbers->sum('closes') }}
            </div>
        </x-table-accordion.td>
    </div>
</div>
