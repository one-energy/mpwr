<div>
    <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
        <h3 class="text-lg text-gray-900">Manage offices</h3>
        <div class="mt-6 max-w-6xl mx-auto px-6">
            <x-search :search="$search" :perPage="false"/>
        </div>
        <div class="grid grid-cols-2 gap-4 max-w-6xl mx-auto px-6">
            <div class="col-span-1">
                <div class="inline-flex grid-cols-6 gap-4 h-8">
                    <div class="col-span-1 py-2">
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="offices_list">Offices</label>
                    </div>
                    <div class="col-span-5">
                        <x-svg.spinner
                            color="#9fa6b2"
                            class="relative hidden top-2 w-6"
                            wire:loading.class.remove="hidden" wire:target="addOfficeToRegion">
                        </x-svg.spinner>
                    </div>
                </div>
                <div class="border-gray-200 border-2 m-1 p-2 rounded-lg h-80 overflow-y-auto cursor-pointer" id="offices_list">
                    @forelse($offices as $office)
                        @if($office->region_id !== $region->id)
                            <div class="hover:bg-gray-100 h-8 p-1 grid grid-cols-6" wire:click="addOfficeToRegion({{$office->id}})">
                                <div class="text-right col-span-5 truncate">
                                    @if($office->region_id)
                                    {{$office->name}} - {{$office->region->name}}
                                    @else
                                    {{$office->name}} - Without region
                                    @endif
                                </div>
                                <div class="float-right col-span-1">
                                    <x-svg.chevron-right class="float-right w-7 text-gray-500"/>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex flex-col justify-center h-8 p-1 hover:bg-gray-100">
                            No offices found...
                        </div>        
                    @endforelse
                </div>
            </div>
            <div class="col-span-1">
                <div class="inline-flex grid-cols-6 gap-4 h-8">
                    <div class="col-span-1 py-2">
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="members_list">{{$region->name}} offices</label>
                    </div>
                    <div class="col-span-5">
                        <x-svg.spinner
                            color="#9fa6b2"
                            class="relative hidden top-2 w-6"
                            wire:loading.class.remove="hidden" wire:target="removeOfficeRegion">
                        </x-svg.spinner>
                    </div>
                </div>
                <div class="border-gray-200 border-2 m-1 p-2 rounded-lg h-80 overflow-y-auto cursor-pointer" id="members_list">
                    @if($this->existsOfficesOnRegion())
                        @foreach($offices as $office)
                            @if($office->region_id === $region->id)
                                <div class="hover:bg-gray-100 h-8 p-1 grid grid-cols-6" wire:click="removeOfficeRegion({{$office->id}})">
                                    <div class="col-span-1">
                                        <x-svg.chevron-left class="w-7 text-gray-500"/>
                                    </div>
                                    <div class="col-span-5 h-full truncate">
                                        {{$office->name}}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="hover:bg-gray-100 h-8 p-1">
                            No offices found on this region...
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
