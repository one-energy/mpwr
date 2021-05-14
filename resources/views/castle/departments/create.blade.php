<x-app.auth :title="__('New Departments')">
    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.departments.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < New Department
            </a>
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.departments.store')">
                <div class="px-8">
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2 mt-2">
                            <x-input label="Department Name" name="name"/>
                        </div>
                        <div class="md:col-span-3 col-span-2">
                            <x-multiselect
                                trackBy="id"
                                labeledBy="full_name"
                                label="Department Managers"
                                name="department_manager_ids[]"
                                :options="$users"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex justify-start mt-6 border-gray-200 px-8">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Add Department
                        </button>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.departments.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
            </x-form>
        </div>
    </div>
</x-app.auth>
