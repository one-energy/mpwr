<x-app.auth :title="__('Dashboard')">
    <div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <div class="px-4 py-5 overflow-y-auto w-full sm:px-6 md:w-2/3 xl:w-4/5">

                    <livewire:area-chart/>

                    <div class="grid grid-flow-col grid-rows-2 grid-cols-1 sm:grid-rows-1 sm:grid-cols-2 mt-12">
                        <div class="flex w-full justify-between sm:justify-start ">
                            <div>
                                <h3 class="text-lg text-gray-900">Customers</h3>
                            </div>
                            @if(user()->role != 'Setter')
                            <div class="flex sm:ml-2">
                                <a class="flex rounded-md bg-green-base text-white items-center pl-3 text-sm h-8" href="{{route('customers.create')}}">
                                    Add Customer
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="-3 -5 40 35">
                                        <circle cx="12" cy="12" r="15" class="fill-current text-green-base"></circle>
                                        <symbol id="add-customer" viewBox="0 0 25 25">
                                            <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"
                                                class="fill-current text-white"/>
                                        </symbol>
                                        <use xlink:href="#add-customer" width="12" height="12" y="6" x="6"/>
                                    </svg>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div>
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
                        <x-modal x-cloak :title="__('Closer Commission')" :raw="true" description="''" :showIcon="false">
                            <div class="flex flex-col justify-end">
                                <button
                                    x-on:click="open = false"
                                    class="rounded-md w-full px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">
                                    Close
                                </button>
                            </div>
                        </x-modal>
                    </div>
                    <div class="mt-6" >
                        <div class="flex items-center justify-end py-2">
                            <div class="flex items-center px-3"><span class="rounded-full h-2 w-2 bg-green-base"></span><span class="text-xs ml-1">Installed and Paid</span></div>
                            <div class="flex items-center px-3"><span class="rounded-full h-2 w-2 bg-gray-700"></span><span class="text-xs ml-1">Signed and pending</span></div>
                            <div class="flex items-center px-3"><span class="rounded-full h-2 w-2 bg-red-500"></span><span class="text-xs ml-1">Canceled</span></div>
                        </div>
                        @forelse ($customers as $customer)
                            <a href="{{route('customers.show', $customer->id)}}">
                                <div class="flex flex-col sm:flex-row justify-between border rounded-md border-gray-400 mb-2 p-2" >
                                    <div class="sm:w-2/3 md:w-3/4">
                                        <div class="whitespace-no-wrap">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                        <div class="whitespace-no-wrap">{{ number_format($customer->epc) }}kW - <i> {{ $customer->date_of_sale->format('D M j Y')}} </i></div>
                                    </div>
                                    <div class="sm:w-1/3 md:w-1/4">
                                        @if($customer->setter_id == user()->id)
                                            <div class="hidden md:block @if($customer->is_active && $customer->panel_sold) bg-green-base @elseif($customer->is_active == false) bg-red-500 @else bg-gray-700 @endif text-white @if($customer->setter_id == user()->id) rounded-full @else rounded-md @endif py-1 px-1 text-center">
                                                $ {{ number_format($customer->setterCommission, 2) }} 
                                            </div>
                                        @else
                                            <div
                                                class="@if($customer->is_active && $customer->panel_sold) bg-green-base @elseif($customer->is_active == false) bg-red-500 @else bg-gray-700 @endif text-white @if($customer->setter_id == user()->id) rounded-full @else rounded-md @endif py-1 px-1 text-center">
                                                $ {{ number_format($customer->sales_rep_comission, 2) }} 
                                            </div>
                                        @endif
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
                <div class="hidden md:block md:w-1/3 xl:w-1/5 break-words">
                    <x-profile.show-profile-information :userLevel="$userLevel" :userEniumPoints="$userEniumPoints"  :stockPoints="$stockPoints"/>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>
