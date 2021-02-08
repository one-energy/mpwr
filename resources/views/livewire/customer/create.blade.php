<div>
    <div>
        <div class="max-w-6xl py-5 mx-auto sm:px-6 lg:px-8">
            <x-link :href="route('home')" color="gray" class="inline-flex items-center text-sm font-medium leading-5 border-b-2 border-green-base hover:border-green-500">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Dashboard')
            </x-link>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('customers.store')" post>
                @csrf
                @if(user()->role == 'Admin' || user()->role == 'Owner')
                    <x-select wire:model="departmentId" label="Department" name="departmentId">
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('departmentId') == $department ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </x-select>
                @endif
                <div >
                    <input type="hidden" value="{{ $openedById }}" name="opened_by_id">
                    <div class="sm:grid sm:grid-cols-2 sm:row-gap-6 sm:col-gap-4 mt-6 md:grid-cols-6">
                        <div class="col-span-2 md:col-span-3">
                            <x-input wire:model="customer.first_name" label="Customer First Name" name="first_name"></x-input>
                        </div>

                        <div class="col-span-2 md:col-span-3">
                            <x-input wire:model="customer.last_name" label="Customer Last Name" name="last_name"></x-input>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <x-input-add-on wire:model="customer.system_size" label="System Size" name="system_size" addOn="kW"></x-input>
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

                        <div class="col-span-2 md:col-span-3">
                            <x-input wire:model="customer.adders" label="Adders Total" name="adders" step="0.01" type="number"></x-input>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <x-input-currency wire:model="customer.epc" label="EPC" name="epc" observation="Sold Price"></x-input>
                        </div>

                        <div class="col-span-1">
                            <x-select wire:model="customer.financing_id" label="Financing" name="financing_id">
                                @if (old('financing') == '')
                                    <option value="" selected>None</option>
                                @endif
                                @foreach($financings as $financing)
                                <option value="{{ $financing->id }}" {{ old('financing_id') == $financing->id ? 'selected' : '' }}>
                                        {{ $financing->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        @if($customer->financing_id == 1)
                            <div class="col-span-1">
                                <x-select wire:model="customer.financer_id" label="Financer" name="financer_id">
                                    @if (old('financer') == '')
                                        <option value="" selected>None</option>
                                    @endif
                                    @foreach($financers as $financer)
                                        <option value="{{ $financer->id }}" {{ old('financing') == $financer->id ? 'selected' : '' }}>
                                            {{ $financer ->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        @endif

                        @if($customer->financer_id == 1)
                            <div class="w-full col-span-1 md:col-span-2">
                                <x-select wire:model="customer.term_id" label="Term" name="term_id" readonly>
                                    @if (old('term_id') == '')
                                        <option value="" selected>None</option>
                                    @endif
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}" {{ old('term_id') == $term->id ? 'selected' : '' }}>
                                            {{ $term->value }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        @endif

                        <div class="col-span-2 md:col-span-3">
                            <x-select wire:change="getSetterRate($event.target.value)" wire:model="customer.setter_id" label="Setter" name="setter_id">
                                <option value="">None</option>
                                @foreach($users as $setter)
                                    @if($setter->role == 'Setter')
                                        <option value="{{$setter->id}}">{{$setter->first_name}} {{$setter->last_name}}</option>
                                    @endif
                                @endforeach
                            </x-select>
                        </div>

                        <div class="col-span-2 md:col-span-3">
                            <x-input-currency wire:model="customer.setter_fee" label="Setter Fee" name="setter_fee" value="{{$setterFee}}" readonly></x-input>
                        </div>

                        <div class="col-span-2 md:col-span-3">
                            <x-select wire:change="getSalesRepRate($event.target.value)" wire:model="customer.sales_rep_id" label="Sales Rep" name="sales_rep_id">
                                <option value="">None</option>
                                @foreach($users as $rep)
                                    <option value="{{$rep->id}}">{{$rep->first_name}} {{$rep->last_name}}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div  class="col-span-2 md:col-span-3">
                            <x-input-currency wire:model="customer.sales_rep_fee" label="Sales Rep Fee" name="sales_rep_fee"></x-input>
                        </div>

                        @if($customer->financer_id == 1)
                            <div class="col-span-1 col-start-4">
                                <x-input-currency wire:model="customer.enium_points" label="Enium Points" name="enium_points" readonly></x-input>
                            </div>
                        @endif

                        <div class="col-span-2 col-start-5">
                            <x-input-currency  wire:model="customer.sales_rep_comission" label="Sales Rep Comission" name="sales_rep_comission"></x-input>
                        </div>
                    </div>
                </div>
                <div class="pt-5 mt-8 border-t border-gray-200">
                    <div class="flex justify-start">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-gray-900 border border-transparent rounded-md hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray">
                                Submit
                            </button>
                        </span>
                        <span class="inline-flex ml-3 rounded-md shadow-sm">
                            <a href="{{route('home')}}" class="px-4 py-2 text-sm font-medium leading-5 text-gray-800 transition duration-150 ease-in-out border border-transparent rounded-md hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray">
                                Cancel
                            </a>
                        </span>
                    </div>
                </div>
            </x-form>
        </div>
    </div>
</div>
