<div>
    @dump($selected)
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
                    wire:click.stop="collapseRegion({{$regionIndex}})" wire:key="region-{{$region['id']}}">
                    <x-table-accordion.default-td-arrow class="table-cell" :open="$region['itsOpen']">
                        <x-checkbox label="{{$region['name']}}" name="itsOpenRegions.{{$regionIndex}}.selected" wire wire:click.stop="" wire:key="regioncheckbox-{{$region['id']}}"/>
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
                             wire:click.stop="collapseOffice({{$regionIndex}}, {{$officeIndex}})" wire:key="office-{{$office['id']}}">
                            <x-table-accordion.child-td-arrow class="table-cell" :open="$office['itsOpen']">
                                <div class="flex" x-data>
                                    <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2" id="user-{{$user['id']}}" type="checkbox" @change="$wire.selectOffice({{$regionIndex}}, {{$officeIndex}})" checked>
                                    <label for="user-{{$user['id']}}">{{$user['full_name']}}</label>
                                </div>
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
                                <div class="table-row hover:bg-gray-100" wire:key="user-{{$user['id']}}">
                                    <x-table-accordion.td class="table-cell pl-28">
                                        <div class="flex" x-data>
                                            <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2" id="user-{{$user['id']}}" type="checkbox" @change="$wire.selectUser({{$regionIndex}}, {{$officeIndex}}, {{$userIndex}})" checked>
                                            <label for="user-{{$user['id']}}">{{$user['full_name']}}</label>
                                        </div>
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
