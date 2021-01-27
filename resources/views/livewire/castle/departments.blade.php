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
                                    <x-table.th-searchable by="departments.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Department')
                                    </x-table.th>
                                    <x-table.th>
                                        @lang('Department Admin')
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
                                            <x-link :href="route('castle.departments.edit', $department)" class="text-sm">Edit</x-link>
                                        </x-table.td>
                                        <x-table.td>
                                            <x-form :route="route('castle.departments.destroy', $department->id)" delete
                                                    x-data="{deleting: false}">
                                            <x-link color="red" class="text-sm" type="button"
                                                    x-show="!deleting"
                                                    x-on:click="$dispatch('confirm', {from: $event.target})"
                                                    x-on:confirmed="deleting = true; $el.submit()"
                                                >Delete</x-link>
                                            <span x-cloak x-show="deleting" class="text-gray-400">Deleting ...</span>
                                            </x-form>
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                    </div>

                    <x-confirm
                        x-cloak
                        :title="__('Delete department')"
                        :description="__('Are you sure you want to delete this department?')"
                    ></x-confirm>
                </div>
            </div>
        </div>
    </div>
</div>
