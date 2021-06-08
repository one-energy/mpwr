<x-app.auth :title="__('Edit Region')">
    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.regions.index') }}"
               class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Region
            </a>
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-form class="px-8" :route="route('castle.regions.update', $region)" put>
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Region Name" name="name" value="{{ $region->name }}"/>
                        </div>
                        @if(user()->notHaveRoles(['Admin', 'Owner']))
                            <div class="md:col-span-3 col-span-2">
                                <x-input
                                    label="Department"
                                    :value="$region->department->name"
                                    name="department_id"
                                    disabled
                                />
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <x-input
                                    label="Department"
                                    :value="$region->department->name"
                                    name="department_id"
                                    disabled
                                />
                            </div>
                        @endif

                        @if(user()->notHaveRoles(['Admin', 'Owner']))
                            <div class="md:col-span-3 col-span-2 @if(user()->hasRole('Region Manager')) hidden @endif">
                                <span class="block text-sm font-medium leading-5 text-gray-700 mb-1">Managers</span>
                                <div class="border-2 border-gray-200 rounded w-full">
                                    <ul class="space-y-2 p-4">
                                        @foreach($region->managers as $manager)
                                            <li>{{ $manager->full_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <span class="block text-sm font-medium leading-5 text-gray-700">Managers</span>
                                <div class="border-2 border-gray-200 rounded w-full">
                                    <ul class="space-y-2 p-4">
                                        @foreach($region->managers as $manager)
                                            <li>{{ $manager->full_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Update
                        </button>
                    </span>
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.regions.index')}}"
                           class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                    </div>
                </div>
            </x-form>
        </div>
        <livewire:castle.manage-office :region="$region"/>
    </div>
</x-app.auth>
