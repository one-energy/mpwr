<x-app.auth :title="__('New Home Owner')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <x-link :href="route('home')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Dashboard')
            </x-link>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('customers.store')" post>
                @csrf
                <div>
                    <input type="hidden" value="{{ $openedById }}" name="opened_by_id">
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="md:col-span-3 col-span-2">
                        <x-input label="Customer First Name" name="first_name"></x-input>
                    </div>

                    <div class="md:col-span-3 col-span-2">
                        <x-input label="Customer Last Name" name="last_name"></x-input>
                    </div>
            
                    <div class="md:col-span-2 col-span-1">
                        <x-input-add-on label="System Size" name="system_size" addOn="kW"></x-input>
                    </div>

                    <div class="col-span-1">
                        <x-select label="Bill" name="bill">
                            @if (old('bill') == '')
                                <option value="" selected>None</option>
                            @endif
                            @foreach($bills as $bill)
                            <option value="{{ $bill }}" {{ old('bill') == $bill ? 'selected' : '' }}>
                                    {{ $bill }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
            
                    <div class="md:col-span-2 col-span-1">
                        <x-input-currency label="Redline" name="pay"></x-input>
                    </div>

                    <div class="col-span-1">
                        <x-select label="Financing" name="financing">
                            @if (old('financing') == '')
                                <option value="" selected>None</option>
                            @endif
                            @foreach($financings as $financing)
                            <option value="{{ $financing }}" {{ old('financing') == $financing ? 'selected' : '' }}>
                                    {{ $financing }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <x-input label="Adders" name="adders" type="number"></x-input>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <x-input-currency label="EPC" name="epc" observation="Sold Price"></x-input>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <x-select label="Setter" name="setter_id">
                            @if (old('setter_id') == '')
                                <option value="" selected>None</option>
                            @endif
                            @foreach($users as $setter)
                                <option value="{{ $setter->id }}" {{ old('setter_id') == $setter->id ? 'selected' : '' }}>
                                    {{ $setter->first_name }} {{ $setter->last_name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
            
                    <div class="md:col-span-3 col-span-2">
                        <x-input-currency label="Setter Fee" name="setter_fee"></x-input>
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
            </x-form>
        </div>
    </div>
</x-app.auth>