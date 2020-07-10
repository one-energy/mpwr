<x-app.auth :title="__('Edit Home Owner')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{route('home')}}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Home Owner
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form>
                <div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
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
            
                    <div class="sm:col-span-3">
                        <label for="system_size" class="block text-sm font-medium leading-5 text-gray-700">
                        System Size
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="system_size" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            value="10.28kW" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="redline" class="block text-sm font-medium leading-5 text-gray-700">
                        Redline
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="redline" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            value="$3.15" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="adders" class="block text-sm font-medium leading-5 text-gray-700">
                        Adders
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="adders" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$2,000" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="gross_ppw" class="block text-sm font-medium leading-5 text-gray-700">
                        Gross PPW
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="gross_ppw" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="$4.00" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="setter" class="block text-sm font-medium leading-5 text-gray-700">
                        Setter
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="setter" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        value="Jackson Shaw" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
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

                    <div class="sm:col-span-2">
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