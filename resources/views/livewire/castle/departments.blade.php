<div>
    @push('styles')
        <style>
            .manager-popover { display: block; }
            .manager-icon { display: none; }

            @media only screen and (max-width: 909px) {
                .manager-popover { display: none; }
                .manager-icon { display: flex; justify-content: center }
                .table-container { overflow-x: auto }
            }
        </style>
    @endpush

    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg text-gray-900">Manage Departments</h3>
                </div>
                <div>
                    <x-button :href="route('castle.departments.create')" color="green">
                        @lang('Create')
                    </x-button>
                </div>
            </div>

            <x-search :search="$search"/>

            <div class="mt-6">
                <div class="flex flex-col">
                    <x-table class="table-container" :pagination="$departments->links()">
                        <x-slot name="header">
                            <x-table.th-tr>
                                <x-table.th-searchable by="departments.name" :sortedBy="$sortBy"
                                                       :direction="$sortDirection">
                                    @lang('Department')
                                </x-table.th-searchable>
                                <x-table.th>
                                    @lang('Vp')
                                </x-table.th>
                                <x-table.th></x-table.th>
                                <x-table.th></x-table.th>
                            </x-table.th-tr>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($departments as $department)
                                <x-table.tr :loop="$loop">
                                    <x-table.td>{{ $department->name }}</x-table.td>
                                    <x-table.td x-data="{ open: false }" class="relative">
                                        <div class="flex items-center">
                                            @if ($department->managers->isNotEmpty())
                                                <div class="manager-popover">
                                                    <x-popover left :ref="$loop->index">
                                                        <ul class="text-sm space-y-3">
                                                            @foreach($department->managers as $manager)
                                                                <li class="flex" style="white-space: break-spaces">
                                                                    <span> {{ $manager->full_name }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </x-popover>
                                                    <div class="flex items-baseline space-x-1">
                                                        <x-icon
                                                            x-data=""
                                                            @mouseenter="$dispatch('open-popover', {ref: '{{ $loop->index }}'})"
                                                            @mouseleave="$dispatch('close-popover', {ref: '{{ $loop->index }}'})"
                                                            icon="user"
                                                            class="w-3.5 h-auto mr-2.5"
                                                        />
                                                        <span>
                                                                    {{ $this->getManagersName($department->managers) }}
                                                            @if ($department->managers->count() > 3)...@endif
                                                                </span>
                                                    </div>
                                                </div>
                                                <div class="manager-icon">
                                                            <span class="cursor-pointer" wire:click="openManagersListModal({{ $department }})">
                                                               <x-icon icon="user" class="w-3.5 h-auto mr-2.5"/>
                                                            </span>
                                                </div>
                                            @else
                                                &#8212;
                                            @endif
                                        </div>
                                    </x-table.td>
                                    <x-table.td>
                                        <x-link
                                            :href="route('castle.departments.edit', $department)"
                                            class="text-sm"
                                        >
                                            Edit
                                        </x-link>
                                    </x-table.td>
                                    <x-table.td>
                                        <x-link color="red" class="text-sm" type="button"
                                                x-on:click="$dispatch('confirm', {from: $event.target})"
                                                wire:click="setDeletingDepartment({{$department->id}})">
                                            Delete
                                        </x-link>
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                        </x-slot>
                    </x-table>

                    <div x-data="showManagersModalHandler()" @on-show-managers.window="open" x-cloak>
                        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <div
                            x-show="isOpen"
                            @click.away="close"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                <button
                                    @click="close"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
                                    aria-label="Close"
                                >
                                    <x-svg.x class="w-5 h-5" />
                                </button>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-5" id="modal-headline">
                                    Managers List
                                </h3>
                                <template x-if="hasManagers">
                                    <ul class="space-y-2 text-sm mb-3">
                                        <template x-for="manager in managers">
                                            <li x-text="manager.full_name"></li>
                                        </template>
                                        <template x-if="quantity > 4">
                                            <li>...</li>
                                        </template>
                                    </ul>
                                </template>
                                <template x-if="!hasManagers">
                                    <p class="font-light italic text-sm mb-4">
                                        Department without managers
                                    </p>
                                </template>
                                <div class="flex justify-end">
                                    <button
                                        @click="close"
                                        class="rounded-md px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-modal x-cloak :title="__('Delete Department')"
                             :description="$deleteMessage">
                        <x-form wire:submit.prevent="destroy()" class="w-full p-2">
                            <input type="hidden" name="currentName" wire:model="deletingDepartment.name"
                                   value="{{ optional($deletingDepartment)->name }}">

                            @if (optional(optional($deletingDepartment)->regions())->count() || optional(optional($deletingDepartment)->users())->count())
                                <x-input class="pb-2" label="Department Name" name="deletingName" wire/>
                            @endif
                            <div class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto justify-end space-x-2">
                                <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                    <button wire:click="setDeletingDepartment" type="button" x-on:click="open = false"
                                            class=" rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">
                                        Cancel
                                    </button>
                                </div>
                                <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                    <button type="submit"
                                            class="rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50">
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
