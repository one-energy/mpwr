<x-app.auth :title="__('Edit Home Owner')">
    <div>
        <div x-data="{openModal: false, loading: false}">
            <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
                <a href="{{route('home')}}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                    < Edit Home Owner
                </a>
            </div>
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <x-form :route="route('customers.update', $customer->id)" put>
                    @csrf
                    <div>
                        <div class="mt-6 grid sm:grid-cols-2 row-gap-6 col-gap-4 md:grid-cols-6">
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input label="First Name" name="first_name" value="{{ $customer->first_name }}"></x-input>
                        </div>
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input label="Last Name" name="last_name" value="{{ $customer->last_name }}"></x-input>
                        </div>
                
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input-add-on label="System Size" name="system_size" addOn="kW" value="{{ $customer->system_size }}"></x-input-add-on>
                        </div>
    
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input-currency label="Redline" name="redline" value="{{ $customer->redline }}"></x-input-currency>
                        </div>
    
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input-currency label="Adders" name="adders" value="{{ $customer->adders }}"></x-input-currency>
                        </div>
    
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input-currency label="EPC" name="epc" value="{{ $customer->epc }}"></x-input-currency>
                        </div>
    
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-select label="Setter" name="setter_id">
                                @if (old('setter_id') == '')
                                    <option selected></option>
                                @endif
                                @foreach($users as $setter)
                                    <option value="{{ $setter->id }}" {{ old('setter_id', $customer->setter_id) == $setter->id ? 'selected' : '' }}>
                                        {{ $setter->first_name }} {{ $setter->last_name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
    
                        <div class="md:col-span-3 sm:cols-span-2">
                            <x-input-currency label="Setter Fee" name="setter_fee" value="{{ $customer->setter_fee }}"></x-input-currency>
                        </div>

                        <div class="md:col-span-4 sm:cols-span-2 flex items-center justify-between">
                            <input type="hidden" name="panel_sold" value="0">
                            <x-checkbox label="Panel Sold" name="panel_sold" :checked="old('panel_sold', $customer->panel_sold)"></x-checkbox>
                        </div>
    
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                            Setter Fee
                            </label>
                            <div class="mt-3">
                            <span class="block w-full font-bold transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                ${{ $customer->setter_fee }}
                            </span>
                            </div>
                        </div>
    
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                            Your Commission
                            </label>
                            <div class="mt-3">
                            <span class="block w-full font-bold transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                ${{ $customer->commission }}
                            </span>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-start">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Update
                            <button>
                        </span>
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            @if($customer->is_active == true)
                                <a href="#" x-on:click="openModal = true; loading = true" x-show="!openModal" class="py-2 px-4 border-2 border-red-500 rounded-md text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out">
                                    Set as Canceled
                                </a>
                                <span x-show="loading" class="text-gray-400 ml-3 mt-2">Inacticating ...</span>
                            @else
                                <a href="#" x-on:click="openModal = true; loading = true" x-show="!openModal" class="py-2 px-4 border-2 border-green-base rounded-md text-sm leading-5 font-medium rounded-md text-green-base hover:text-green-dark hover:border-green-dark focus:outline-none focus:border-green-500 focus:shadow-outline-red active:bg-green-50 transition duration-150 ease-in-out">
                                    Set as Active
                                </a>
                                <span x-show="loading" class="text-gray-400 ml-3 mt-2">Activating ...</span>
                            @endif
                        </span>
                    </div>
                    </div>
                </x-form>
            </div>
            <div x-cloak x-show="openModal" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
            
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <x-form :route="route('customers.active', $customer->id)" put>
                        @csrf
                    <input type="hidden" id="active" name="active" value="{{ $customer->is_active ? true : false }}">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                            @if($customer->is_active == true)
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            @else
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            @endif
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Set as @if($customer->is_active == true)canceled @else active @endif customer <span class="font-bold">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                                </h3>
                                <div class="mt-2">
                                <p class="text-sm leading-5 text-gray-500">
                                    Are you sure you want to @if($customer->is_active == true)inactive @else activate @endif this customer?
                                </p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                @if($customer->is_active == true)
                                <button x-on:click="openModal = false" type="submit" class="inline-flex justify-center py-2 px-4 border-2 border-red-500 rounded-md text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out">
                                    {{ __('Set as Canceled') }}
                                </button>
                                @else
                                <button x-on:click="openModal = false" type="submit" class="inline-flex justify-center py-2 px-4 border-2 border-green-base rounded-md text-sm leading-5 font-medium rounded-md text-green-base hover:text-green-dark hover:border-green-dark focus:outline-none focus:border-green-500 focus:shadow-outline-red active:bg-green-50 transition duration-150 ease-in-out">
                                    {{ __('Set as Active') }}
                                </button>
                                @endif
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button x-on:click="openModal = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    Cancel
                                </button>
                            </span>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
        @if (session('message'))
            <x-alert class="mb-4">
                {{ session('message') }}
            </x-alert>
        @endif
    </div>
</x-app.auth>