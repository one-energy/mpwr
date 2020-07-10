<x-app.auth :title="__('New Home Owner')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{route('home')}}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < New Home Owner
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form>
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="md:col-span-3 col-span-2">
                        <label for="home_owner" class="block text-sm font-medium leading-5 text-gray-700">
                        Home Owner
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="home_owner" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>
            
                    <div class="md:col-span-2 col-span-1">
                        <label for="system_size" class="block text-sm font-medium leading-5 text-gray-700">
                        System Size
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="system_size" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label for="bill" class="block text-sm font-medium leading-5 text-gray-700">
                        Bill
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <select id="bill" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option></option>
                            <option>$100</option>
                            <option>$200</option>
                            <option>$300</option>
                        </select>
                        </div>
                    </div>
            
                    <div class="md:col-span-2 col-span-1">
                        <label for="pay" class="block text-sm font-medium leading-5 text-gray-700">
                        Pay
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="pay" type="pay" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label for="financing" class="block text-sm font-medium leading-5 text-gray-700">
                        Financing
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <select id="financing" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option></option>
                            <option>$100</option>
                            <option>$200</option>
                            <option>$300</option>
                        </select>
                        </div>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <label for="adders" class="block text-sm font-medium leading-5 text-gray-700">
                        Adders
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="adders" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <label for="gross_ppw" class="block text-sm font-medium leading-5 text-gray-700">
                        Gross PPW
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="gross_ppw" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <label for="setter" class="block text-sm font-medium leading-5 text-gray-700">
                        Setter
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="setter" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <label for="setter_fee" class="block text-sm font-medium leading-5 text-gray-700">
                        Setter Fee
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                        <input id="setter_fee" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                    </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Add Home Owner
                        </button>
                        </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('home')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
                </div>
            </form>
        </div>
    </div>
</x-app.auth>