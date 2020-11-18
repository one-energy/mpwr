<x-app.auth :title="__('New Office')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.rates.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Manage Rates
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.rates.update', $rate)" put>
                @csrf
                <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="md:col-span-6 col-span-2">
                        <x-input label="Name" name="name" value="{{$rate->name}}"></x-input>
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
                        <x-input label="Time" name="time" value="{{$rate->time}}"></x-input>
                    </div>
                    <div class="md:col-span-3 col-span-2">
                        <x-input-currency :label="__('Rate')" name="rate" value="{{$rate->rate}}"></x-input>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Save
                        </button>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.offices.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
                </div>
            </x-form>
        </div>
    </div>
</x-app.auth>