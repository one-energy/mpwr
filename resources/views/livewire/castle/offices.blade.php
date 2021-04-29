<div>
    <div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <h3 class="text-lg text-gray-900">Manage Offices</h3>
                    </div>
                    @if (user()->notHaveRoles(['Office Manager']))
                        <div>
                            <x-button :href="route('castle.offices.create')" color="green">
                                @lang('Create')
                            </x-button>
                        </div>
                    @endif
                </div>

                <x-search :search="$search"/>

                <div class="mt-6">
                    <div class="flex flex-col">
                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                            <div class="align-middle inline-block min-w-full overflow-hidden">
                                <x-table :pagination="$offices->links()">
                                    <x-slot name="header">
                                        <x-table.th-tr>
                                            @if (user()->hasAnyRole(['Admin', 'Owner']))
                                                <x-table.th-searchable by="offices.name" :sortedBy="$sortBy"
                                                                       :direction="$sortDirection">
                                                    @lang('Department')
                                                </x-table.th-searchable>
                                            @endif
                                            <x-table.th>
                                                @lang('Office')
                                            </x-table.th>
                                            <x-table.th>
                                                @lang('Region')
                                            </x-table.th>
                                            <x-table.th>
                                                @lang('Manager')
                                            </x-table.th>
                                            <x-table.th></x-table.th>
                                            <x-table.th></x-table.th>
                                        </x-table.th-tr>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach ($offices as $office)
                                            <x-table.tr :loop="$loop">
                                                @if (user()->hasAnyRole(['Admin', 'Owner']))
                                                    <x-table.td>{{ $office->region->department->name }}</x-table.td>
                                                @endif
                                                <x-table.td>{{ $office->name }}</x-table.td>
                                                <x-table.td>{{ $office->region->name }}</x-table.td>
                                                    <x-table.td x-data="{ open: false }" class="relative">
                                                        @if ($office->managers->isNotEmpty())
                                                            <div class="hidden md:block">
                                                                <div
                                                                    class="bg-gray-200 rounded shadow-xl z-10 h-auto p-4 space-y-2 absolute top-1.5"
                                                                    style="left: -180px; max-width: 200px; width: 200px"
                                                                    x-cloak
                                                                    x-transition:enter="transition ease-out duration-300"
                                                                    x-transition:enter-start="opacity-0 transform scale-90"
                                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                                    x-transition:leave="transition ease-in duration-300"
                                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                                    x-transition:leave-end="opacity-0 transform scale-90"
                                                                    x-show="open"
                                                                >
                                                                    <ul class="text-sm space-y-3">
                                                                        @foreach($office->managers as $manager)
                                                                            <li class="flex" style="white-space: break-spaces">
                                                                                <span>- {{ $manager->full_name }}</span>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                                <div class="flex items-baseline space-x-1">
                                                                    <x-icon
                                                                        @mouseenter="open = true"
                                                                        @mouseleave="open = false"
                                                                        icon="user"
                                                                        class="w-3.5 h-auto mr-2.5"
                                                                    />
                                                                    <span>
                                                                    {{ $this->getManagersName($office->managers) }}
                                                                        @if ($office->managers->count() > 3)...@endif
                                                                </span>
                                                                </div>
                                                            </div>
                                                            <div class="block md:hidden">
                                                            <span wire:click="openManagersListModal({{ $office }})">
                                                               <x-icon icon="user" class="w-3.5 h-auto mr-2.5"/>
                                                            </span>
                                                            </div>
                                                        @else
                                                            &#8212;
                                                        @endif
                                                    </x-table.td>
                                                <x-table.td>
                                                    <x-link :href="route('castle.offices.edit', $office)"
                                                            class="text-sm">Edit
                                                    </x-link>

                                                </x-table.td>
                                                <x-table.td>
                                                    @if (user()->hasAnyRole(['Admin', 'Owner', 'Department Manager', 'Region Manager']))
                                                        <x-link color="red" class="text-sm" type="button"
                                                                wire:click="setDeletingOffice({{$office->id}})"
                                                                x-on:click="$dispatch('confirm')">
                                                            Delete
                                                        </x-link>
                                                    @endIf
                                                </x-table.td>
                                            </x-table.tr>
                                        @endforeach
                                    </x-slot>
                                </x-table>
                            </div>
                        </div>

                        <x-modal x-cloak :title="__('Delete Office')"
                                 :description="$deleteMessage">
                            <x-form wire:submit.prevent="destroy()" class="w-full p-2">
                                <input type="hidden" name="currentName" wire:model="deletingOffice.name"
                                       value="{{ optional($deletingOffice)->name }}">

                                @if ($deletingOffice && optional(optional($deletingOffice)->users()->count()))
                                    <x-input class="pb-2" label="Office Name" name="deletingName" wire/>
                                @endif
                                <div class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto justify-end space-x-2">
                                    <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button wire:click="setDeletingOffice" type="button" x-on:click="open = false"
                                                class="rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">
                                            Cancel
                                        </button>
                                    </div>
                                    <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button type="submit"
                                                class=" rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50">
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </x-form>

                        </x-modal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const showManagersModalHandler = () => ({
            show: false,
            managers: [],
            quantity: 0,
            open(event) {
                this.managers = event.detail.managers;
                this.quantity = event.detail.quantity;
                this.show = true;
            },
            close() {
                this.show = false;
            },
            get isOpen() {
                return this.show === true;
            },
            get hasManagers() {
                return !!this.managers.length;
            }
        })
    </script>
@endpush
