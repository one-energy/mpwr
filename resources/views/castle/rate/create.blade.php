<x-app.auth :title="__('New Office')">
    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.rates.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Manage Compensations
            </a>
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.rates.store')">
                <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6 px-8">
                    <div class="md:col-span-6 col-span-2">
                        <x-input label="Title" name="name"/>
                    </div>
                    @if(user()->role != "Admin" && user()->role != "Owner")
                        <div class="md:col-span-3 col-span-2 hidden">
                            <x-select label="Department" name="department_id">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department', user()->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department['name'] }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                    @else
                        <div class="md:col-span-3 col-span-2">
                            <x-select label="Department" name="department_id">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department', user()->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department['name'] }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                    @endif
                    <div class="md:col-span-3 col-span-2">
                        <x-input type="number" label="Systems Installed" name="time"/>
                    </div>
                    <div class="md:col-span-3 col-span-2">
                        <x-input-currency :label="__('Pay Rate ($/W)')" name="rate"/>
                    </div>
                    <div class="md:col-span-3 col-span-2">
                        <x-select label="Role" name="role">
                            @foreach($roles as $role)
                                <option value="{{ $role['name'] }}"  {{ old('role', user()->role) == $role['name'] ? 'selected' : '' }}>
                                    {{ $role['title'] }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
                <div class="flex justify-start mt-6 px-8 border-gray-200">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Add Rate
                        </button>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.rates.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
            </x-form>
        </div>
    </div>
</x-app.auth>
