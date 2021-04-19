@props(['regions'])
@foreach($regions as $region)
    <div id="defaultRow-{{$region->id}}" class="table-row" x-on:click="collapseRow('firstRow-{{$region}}', 'defaultRow-{{$region}}', 'secondRow-{{$region}}')">
        <x-table-accordion.default-td-arrow class="table-cell" by="deparmtent" sortedBy="$sortBy" :index="$region->id">Member</x-table-accordion.td-arrow>
        <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
            @lang('Hours')
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
            @lang('Sets')
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
            @lang('Set Sits')
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
            @lang('Sits')
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
            @lang('Set Closes')
        </x-table-accordion.td>
        <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
            @lang('Closes')
        </x-table-accordion.td>
    </div>
    @forelse($region->offices as $office)
        <div id="firstRow-{{$office->id}}" class="hidden" x-on:click="collapseRow('secondRow-{{$office}}', 'firstRow-{{$office}}')">
            <x-table-accordion.child-td-arrow class="table-cell" by="deparmtent" sortedBy="$sortBy" :index="$office->id">Content</x-table-accordion.td-arrow>
                <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                    @lang('Doors')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                    @lang('Hours')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                    @lang('Sets')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                    @lang('Set Sits')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                    @lang('Sits')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                    @lang('Set Closes')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                    @lang('Closes')
                </x-table-accordion.td>
        </div>
        @forelse($office->users as $user)
            <div id="secondRow-{{$region->id}}-{{$user->id}}" class="hidden">
                <x-table-accordion.td class="table-cell pl-28" by="deparmtent" sortedBy="$sortBy">{{$region->name}}</x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                    @lang('Doors')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                    @lang('Hours')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                    @lang('Sets')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                    @lang('Set Sits')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                    @lang('Sits')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                    @lang('Set Closes')
                </x-table-accordion.td>
                <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                    @lang('Closes')
                </x-table-accordion.td>
            </div>
        @empty
            Empty
        @endforelse
    @empty
        Empty
    @endforelse
@endforeach
