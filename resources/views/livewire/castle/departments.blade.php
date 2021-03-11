<div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full overflow-hidden">
                            <x-table :pagination="$departments->links()">
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
                                            <x-table.td>{{ $department->departmentAdmin->first_name }} {{ $department->departmentAdmin->last_name }}</x-table.td>
                                            <x-table.td>
                                                <x-link :href="route('castle.departments.edit', $department)"
                                                        class="text-sm">Edit
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
