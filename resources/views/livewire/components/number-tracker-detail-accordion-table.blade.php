<div>
    <x-table-accordion class="overflow-x-auto">
        <x-slot name="header">
            <x-table-accordion.th-searchable class="table-cell" by="deparmtent" sortedBy="$sortBy">Member</x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="doors" sortedBy="$sortBy">
                @lang('Doors')
            </x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="hours" sortedBy="$sortBy">
                @lang('Hours')
            </x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="sets" sortedBy="$sortBy">
                @lang('Sets')
            </x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="set_sits" sortedBy="$sortBy">
                @lang('Set Sits')
            </x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="sits" sortedBy="$sortBy">
                @lang('Sits')
            </x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="set_closes" sortedBy="$sortBy">
                @lang('Set Closes')
            </x-table-accordion.th-searchable>
            <x-table-accordion.th-searchable class="table-cell" by="closes" sortedBy="$sortBy">
                @lang('Closes')
            </x-table-accordion.th-searchable>
        </x-slot>
        <x-slot name="body">
                @foreach($regions as $region)
                    <div id="defaultRow-{{$region->id}}" class="table-row" x-on:click="collapseRow('firstRow-{{$region->id}}-', 'defaultRow-{{$region->id}}', 'secondRow-{{$region->id}}-')">
                        <x-table-accordion.default-td-arrow class="table-cell" :index="$region->id">{{$region->name}}</x-table-accordion.td-arrow>
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
                    @forelse($region->offices as $office)
                        <div id="firstRow-{{$region->id}}-{{$office->id}}" class="hidden" x-on:click="collapseRow('secondRow-{{$region->id}}-{{$office->id}}-', 'firstRow-{{$region->id}}-{{$office->id}}')">
                            <x-table-accordion.child-td-arrow class="table-cell" :index="$office->id">{{$region->name}} {{$office->name}}</x-table-accordion.td-arrow>
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
                            <div id="secondRow-{{$region->id}}-{{$office->id}}-{{$user->id}}" class="hidden">
                                <x-table-accordion.td class="table-cell pl-28">{{$region->name}} {{$office->name}}</x-table-accordion.td>
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
                            <div id="secondRow-{{$user->id}}" class="hidden">
                                Empty
                            </div>
                        @endforelse
                    @empty
                        Empty
                    @endforelse
                @endforeach
        </x-slot>
    </x-table-accordion>
</div>
