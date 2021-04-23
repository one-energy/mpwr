<div x-data="initAccordion()" x-init="[bootstrap()]">
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
                        <div class="flex" x-data>
                            <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                   type="checkbox" @change="$wire.selectRegion({{$regionIndex}}); console.log('teste')" checked wire:click.stop="">
                            <label for="region-{{$region['id']}}">{{$region['name']}}</label>
                        </div>
                    </x-table-accordion.td-arrow>
                    <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'doors')}}
                    </x-table-accordion.td>
                    <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'hours')}}
                    </x-table-accordion.td>
                    <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'sets')}}
                    </x-table-accordion.td>
                    <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'set_sits')}}
                    </x-table-accordion.td>
                    <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'sits')}}
                    </x-table-accordion.td>
                    <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'set_closes')}}
                    </x-table-accordion.td>
                    <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                        {{$this->sumRegionNumberTracker($region, 'closes')}}
                    </x-table-accordion.td>
                </div>
                @if($region['itsOpen'])
                    @forelse($region['offices'] as $officeIndex => $office)
                        <div class="table-row cursor-pointer hover:bg-gray-100 @if($office['itsOpen']) bg-gray-100 @endif"
                             wire:click.stop="collapseOffice({{$regionIndex}}, {{$officeIndex}})" wire:key="office-{{$office['id']}}">
                            <x-table-accordion.child-td-arrow class="table-cell" :open="$office['itsOpen']">
                                <div class="flex" x-data>
                                    <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                        type="checkbox" @change="$wire.selectOffice({{$regionIndex}}, {{$officeIndex}})" checked wire:click.stop="">
                                    <label for="office-{{$office['id']}}">{{$office['name']}}</label>
                                </div>
                            </x-table-accordion.td-arrow>
                            <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'doors')}}
                            </x-table-accordion.td>
                            <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'hours')}}
                            </x-table-accordion.td>
                            <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'sets')}}
                            </x-table-accordion.td>
                            <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'set_sits')}}
                            </x-table-accordion.td>
                            <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'sits')}}
                            </x-table-accordion.td>
                            <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'set_closes')}}
                            </x-table-accordion.td>
                            <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                {{$this->sumOfficeNumberTracker($office, 'closes')}}
                            </x-table-accordion.td>
                        </div>
                        @if($office['itsOpen'])
                            @forelse($office['users'] as $userIndex => $user)
                                <div class="table-row hover:bg-gray-100" wire:key="user-{{$user['id']}}">
                                    <x-table-accordion.td class="table-cell pl-28">
                                        <div class="flex" x-data>
                                            <input class="form-checkbox items-center h-4 w-4 text-green-base transition duration-150 ease-in-out mr-2"
                                            type="checkbox" @change="$wire.selectUser({{$regionIndex}}, {{$officeIndex}}, {{$userIndex}})" checked wire:click.stop="">
                                            <label for="user-{{$user['id']}}">{{$user['full_name']}}</label>
                                        </div>
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="doors" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'doors')}}
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="hours" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'hours')}}
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="sets" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'sets')}}
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="set_sits" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'set_sits')}}
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="sits" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'sits')}}
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="set_closes" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'set_closes')}}
                                    </x-table-accordion.td>
                                    <x-table-accordion.td class="table-cell" by="closes" sortedBy="$sortBy">
                                        {{$this->sumUserNumberTracker($user, 'closes')}}
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

@push('scripts')
    <script>
        function initAccordion() {
            return {
                bootstrap () {
                    window.livewire.emit('sumTotalNumbers', @json($totals))
                }
            }
        }
    </script>
@endpush
