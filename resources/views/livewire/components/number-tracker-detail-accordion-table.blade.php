<div>
    <x-table-accordion class="overflow-x-auto">
        <x-slot name="header">
            <x-table-accordion.th-searchable class="table-cell" by="deparmtent" sortedBy="$sortBy"></x-table-accordion.th-searchable>
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
            @foreach($itsOpenRegions as $regionIndex => $region)
                <div class="table-row cursor-pointer hover:bg-gray-100 @if($region['itsOpen']) bg-gray-200 @endif"
                    wire:click.stop="collapseRegion({{$regionIndex}})">
                    <x-table-accordion.default-td-arrow class="table-cell" :open="$region['itsOpen']">
                        <x-checkbox label="{{$region['name']}}" name="itsOpenRegions.{{$regionIndex}}.selected" wire wire:click.stop=""/>
                    </x-table-accordion.td-arrow>
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
                @if($region['itsOpen'])
                    @forelse($region['offices'] as $officeIndex => $office)
                        <div class="table-row cursor-pointer hover:bg-gray-100 @if($office['itsOpen']) bg-gray-100 @endif"
                             wire:click.stop="collapseOffice({{$regionIndex}}, {{$officeIndex}})">
                            <x-table-accordion.child-td-arrow class="table-cell" :open="$office['itsOpen']">
                                <x-checkbox label="{{$office['name']}}" name="itsOpenRegions.{{$regionIndex}}.offices.{{$officeIndex}}.selected" wire wire:click.stop="" />
                            </x-table-accordion.td-arrow>
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
                        @if($office['itsOpen'])
                            @forelse($office['users'] as $userIndex => $user)
                                <div class="table-row hover:bg-gray-100">
                                    <x-table-accordion.td class="table-cell pl-28">
                                        <x-checkbox label="{{$user['first_name']}} {{$user['last_name']}}" name="itsOpenRegions.{{$regionIndex}}.offices.{{$officeIndex}}.users.{{$userIndex}}.selected" wire/>
                                    </x-table-accordion.td>
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
                                <div class="table-row">
                                    Empty
                                </div>
                            @endforelse
                        @endif
                    @empty
                        Empty
                    @endforelse
                @endif
            @endforeach
        </x-slot>
    </x-table-accordion>
</div>
