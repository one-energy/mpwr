<x-app.auth :title="__('Dashboard')">
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
                <div class="px-4 py-5 overflow-y-auto sm:px-6 sm:w-full md:w-2/3">            

                    <livewire:area-chart/>

                    <div class="flex justify-between mt-12">
                        <div class="flex justify-start">
                            <h3 class="text-lg text-gray-900">Customers</h3>
                            @if(user()->role != 'Setter')
                                <a href="{{route('customers.create')}}" class="ml-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
                                        <circle cx="12" cy="12" r="10" class="fill-current text-green-light"></circle>
                                        <symbol id="add-customer" viewBox="0 0 25 25">
                                            <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"
                                                class="fill-current text-green-base"/>
                                        </symbol>
                                        <use xlink:href="#add-customer" width="12" height="12" y="6" x="6"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <form action="{{ route('home') }}">
                            <div class="flex justify-end" x-data="{ sortOptions: false }">
                                <label for="sort_by" class="block mt-1 text-xs font-medium leading-5 text-gray-700">
                                    Sort by:
                                </label>
                                <div class="relative inline-block ml-2 text-left">
                                    <select id="sort_by"
                                            name="sort_by"
                                            onchange="this.form.submit()"
                                            class="block w-full py-1 text-lg text-gray-500 transition duration-150 ease-in-out rounded-lg form-select">
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
                        <div class="flex items-center justify-end py-2">
                            <div class="flex items-center px-3"><span class="rounded-full h-2 w-2 bg-green-base"></span><span class="text-xs ml-1">Active Customers</span></div>
                            <div class="flex items-center px-3"><span class="rounded-full h-2 w-2 bg-gray-700"></span><span class="text-xs ml-1">Prospective Customers</span></div>
                            <div class="flex items-center px-3"><span class="rounded-full h-2 w-2 bg-red-500"></span><span class="text-xs ml-1">Inactive Customers</span></div>
                        </div>
                        @forelse ($customers as $customer)
                            <a href="{{route('customers.show', $customer->id)}}">
                                <div
                                    class="flex grid justify-between grid-cols-4 row-gap-1 col-gap-4 p-2 m-1 border-2 border-gray-200 rounded-lg md:grid-cols-9 hover:bg-gray-50">
                                    <div class="col-span-6 md:col-span-7">
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </div>
                                    <div class="col-span-1 row-span-2 md:col-span-2">
                                        <div
                                            class="@if($customer->is_active && $customer->panel_sold) bg-green-base @elseif($customer->is_active == false) bg-red-500 @else bg-gray-700 @endif text-white rounded-md py-1 px-1 text-center">
                                            $ {{ number_format($customer->commission) }}
                                        </div>
                                    </div>
                                    <div class="col-span-7 text-xs text-gray-600">
                                        {{ number_format($customer->epc) }}kW
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="h-96 ">
                                <div class="flex justify-center align-middle">
                                    <div class="text-sm text-center text-gray-700">
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
                    <x-profile.show-profile-information/>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>