<x-app.auth :title="__('Edit Home Owner')">
    <div>
        @if (session()->has('message'))
            <div class="fixed inset-0 flex items-center justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start mt-14 z-50">
                <div x-data="{show: true}" @click.away="show = false" class="max-w-md w-full bg-white shadow rounded-lg pointer-events-auto">
                    <div class="rounded-lg shadow-xs overflow-hidden" x-show="show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="p-4">
                        <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm leading-5 font-medium text-gray-900">
                                {{session('message')}}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false;" class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        </div>
                    </div>
                    </div>
                </div> 
            </div>
        @endif
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{route('home')}}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Home Owner
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form>
                <div>
                    <div class="mt-6 grid sm:grid-cols-2 row-gap-6 col-gap-4 md:grid-cols-6">
                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="home_owner" class="block text-sm font-medium leading-5 text-gray-700">
                        Home Owner
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="home_owner" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        @if($customer == 1)
                            value="Donna Walker"
                        @else
                            value="Chris Williams"
                        @endif />
                        </div>
                    </div>
            
                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="system_size" class="block text-sm font-medium leading-5 text-gray-700">
                        System Size
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="system_size" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            value="10.28kW" />
                        </div>
                    </div>

                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="redline" class="block text-sm font-medium leading-5 text-gray-700">
                        Redline
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="redline" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            value="$3.15" />
                        </div>
                    </div>

                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="adders" class="block text-sm font-medium leading-5 text-gray-700">
                        Adders
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="adders" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$2,000" />
                        </div>
                    </div>

                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="epc" class="block text-sm font-medium leading-5 text-gray-700">
                        EPC
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="epc" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$4.00" />
                        </div>
                    </div>

                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="setter" class="block text-sm font-medium leading-5 text-gray-700">
                        Setter
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="setter" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="Jackson Shaw" />
                        </div>
                    </div>

                    <div class="md:col-span-3 sm:cols-span-2">
                        <label for="setter_fee" class="block text-sm font-medium leading-5 text-gray-700">
                        Setter Fee
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="setter_fee" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$0.30" />
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="financing" class="block text-sm font-medium leading-5 text-gray-700">
                        Setter Fee
                        </label>
                        <div class="mt-3">
                        <input id="setter_fee" class="block w-full font-bold transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$1,500" />
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <label for="financing" class="block text-sm font-medium leading-5 text-gray-700">
                        Your Commission
                        </label>
                        <div class="mt-3">
                        <input id="setter_fee" class="block w-full font-bold transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$8,900" />
                        </div>
                    </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Update
                        </button>
                        </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="#" class="py-2 px-4 border-2 border-red-500 rounded-md text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-gray active:bg-gray-50 transition duration-150 ease-in-out">
                            Set as Canceled
                        </a>
                    </span>
                </div>
                </div>
            </form>
        </div>
    </div>
</x-app.auth>