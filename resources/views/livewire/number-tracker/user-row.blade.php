<div>
    <div
        class="grid parent-scope cursor-pointer"
        style="grid-template-columns: repeat(4, minmax(80px, 80px)) repeat(8, minmax(153px, 153px))"
    >
        <x-table-accordion.td class="scope-child col-span-4">
            <div class="flex ml-28">
                <div class="flex" x-data wire:loading.remove>
                    <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                            wire:model="isSelected" wire:click="selectUser()" type="checkbox" wire:click.stop="">
                </div>
                <x-svg.spinner wire:loading color="#9fa6b2" class="self-center hidden w-5 mr-2" />
                @if($user->deleted_at != null)
                    <x-icon class="mr-2 w-6 h-6" icon="user-blocked"/>
                @endif
                <label>{{$user->fullName}}</label>
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('hours_worked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('doors') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('hours_knocked') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif" class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('sets') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('sats') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif" class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('set_closes') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('closer_sits') }}
            </div>
        </x-table-accordion.td>
        <x-table-accordion.td class="scope-child">
            <x-svg.spinner
                color="#9fa6b2"
                class="self-center hidden w-5">
            </x-svg.spinner>
            <div class="@if($userDailyNumbers[0]['deleted_at'] != null) text-red-500 @endif">
                {{$userDailyNumbers->sum('closes') }}
            </div>
        </x-table-accordion.td>
    </div>
</div>
