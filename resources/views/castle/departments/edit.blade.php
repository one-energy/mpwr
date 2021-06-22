<x-app.auth :title="__('Edit Department')">
    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.departments.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Department
            </a>
        </div>
        <div class="flex flex-col space-y-4">
            <div>
                <x-input label="Department Name" name="name" value="{{ $department->name }}"/>
            </div>

            <div>
                <span class="block text-sm font-medium leading-5 text-gray-700">Managers</span>
                <div class="border-2 border-gray-200 rounded w-full md:w-3/6">
                    <ul class="space-y-2 p-4">
                        @foreach($department->managers as $manager)
                            <li>{{ $manager->full_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>
