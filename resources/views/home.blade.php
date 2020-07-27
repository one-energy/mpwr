<x-app.auth :title="__('Dashboard')">
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
            <div class="px-4 py-5 sm:px-6 sm:w-full md:w-2/3 overflow-y-auto">
                <div class="flex justify-between">
                <h3 class="text-lg text-gray-900">Projected Income</h3>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <circle cx="20" cy="20" r="14" class="text-green-base fill-current"></circle>
                    <symbol id="panel" viewBox="0 0 25 25">
                        <path d="M6 18h-2v5h-2v-5h-2v-3h6v3zm-2-17h-2v12h2v-12zm11 7h-6v3h2v12h2v-12h2v-3zm-2-7h-2v5h2v-5zm11 14h-6v3h2v5h2v-5h2v-3zm-2-14h-2v12h2v-12z" class="text-white fill-current" />
                    </symbol>
                    <use xlink:href="#panel" width="14" height="14" y="13" x="13" />
                    </svg>
                </a>
                </div>
        
                <!-- Area Chart -->
                @livewire('area-chart')
        
                <!-- Customers List -->
                <div class="flex justify-between mt-12">
                <div class="flex justify-start">
                    <h3 class="text-lg text-gray-900">Customers</h3>
                    <a href="{{route('customers.create')}}" class="ml-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
                        <circle cx="12" cy="12" r="10" class="text-green-light fill-current"></circle>
                        <symbol id="add-customer" viewBox="0 0 25 25">
                        <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z" class="text-green-base fill-current"/>
                        </symbol>
                        <use xlink:href="#add-customer" width="12" height="12" y="6" x="6" />
                    </svg>
                    </a>
                </div>
                <form  action="{{ route('home') }}">
                    <div class="flex justify-end" x-data="{ sortOptions: false }">
                        <label for="sort_by" class="block text-xs font-medium leading-5 text-gray-700 mt-1">
                        Sort by:
                        </label>
                        <div class="relative inline-block text-left ml-2">
                            <select id="sort_by"
                                    name="sort_by"
                                    onchange="this.form.submit()"
                                    class="form-select block w-full transition duration-150 ease-in-out text-gray-500 text-lg py-1 rounded-lg">
                                @foreach($sortTypes as $type)
                                    <option value="{{$type['index']}}"
                                        @if(request('sort_by') == $type['index']) selected @endif>
                                        {{$type['value']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                </div>
                <div class="mt-6">
                    @forelse ($customers as $customer)
                    <a href="{{route('customers.show', $customer->id)}}">
                        <div class="flex justify-between grid md:grid-cols-9 grid-cols-4 row-gap-1 col-gap-4 hover:bg-gray-50 border-gray-200 border-2 m-1 p-2 rounded-lg">
                            <div class="md:col-span-7 col-span-6">
                                {{ $customer->first_name }} {{ $customer->last_name }}
                            </div>
                            <div class="md:col-span-2 col-span-1 row-span-2">
                            <div class="@if($customer->is_active != 1) bg-red-500 @else bg-green-base @endif text-white rounded-md py-1 px-1 text-center">
                                $ {{ $customer->commission }}
                            </div>
                            </div>
                            <div class="text-xs text-gray-600 col-span-7">
                                {{ $customer->epc }}kW
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="h-96 ">
                        <div class="flex align-middle justify-center">
                            <div class="text-gray-700 text-sm text-center">
                                <x-svg.draw.empty></x-svg.draw.empty>
                                No data yet.
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        
            <!-- Personal Data -->
            <div class="hidden md:block">
                @include('components\profile\show-profile-information')
            </div>
            </div>
        </div>
    </div>
</x-app.auth>