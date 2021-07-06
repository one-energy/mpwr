<div>
    <div class="max-w-8xl py-5 mx-auto px-6 lg:px-8">
        <x-link :href="route('home')" color="gray" class="inline-flex items-center text-sm font-medium leading-5 border-b-2 border-green-base hover:border-green-500">
            <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Dashboard')
        </x-link>
    </div>
    <div class="max-w-8xl mx-auto px-6 lg:px-8">
        <form wire:submit.prevent="store">
            <div class="grid grid-cols-2 gap-4 sm:col-gap-4 md:grid-cols-6 px-8">
                @if(user()->role == 'Admin' || user()->role == 'Owner')
                    <div class="col-span-2 md:col-span-6" wire:key="departmentId">
                        <x-select wire:model="departmentId" label="Department" name="departmentId">
                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}" {{ old('departmentId') == $department ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                @endif

                <input type="hidden" value="{{ $openedById }}" name="opened_by_id"/>
                <div class="col-span-2 md:col-span-3">
                    <x-input wire:model="customer.first_name" label="Customer First Name" name="customer.first_name"/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input wire:model="customer.last_name" label="Customer Last Name" name="customer.last_name"/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-calendar wire label="Date of Sale" name="customer.date_of_sale"/>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <x-input-add-on wire:model="customer.system_size" label="System Size" name="system_size" maxSize="100000" addOn="kW" name="customer.system_size"/>
                </div>

                <div class="col-span-1">
                    <x-select wire:model="customer.bill" label="Bill" name="customer.bill">
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
                    <x-select wire:model="customer.financing_id" label="Financing" name="customer.financing_id" wire:key="financingId">
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

                <div class="col-span-1 md:col-span-1 md:col-start-4 @if($customer->financing_id != 1) hidden @endif" wire:key="financerId">
                    <x-select wire:model="customer.financer_id" label="Financer" name="customer.financer_id">
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

                <div class="col-span-1 md:col-span-2 @if($customer->financer_id != 1) hidden @endif" wire:key="termId">
                    <x-select wire:model="customer.term_id" label="Term" name="customer.term_id">
                        <option value="" selected>None</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" >
                                {{ $term->value }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.epc" label="EPC" name="customer.epc" observation="Sold Price" maxSize="100000" atEnd="kW"/>
                </div>

                @if(!user()->hasRole("Setter"))
                    <div class="col-span-2 md:col-span-3">
                        <x-input-currency label="Total System Cost" name="total_cost" maxSize="100000" value="{{$customer->totalSoldPrice}}" readonly/>
                    </div>
                @endif
                
                <div class="col-span-2 md:col-span-3" wire:ignore>
                    <x-select-searchable
                        x-on:popup-close="$wire.updatedCustomerSetterId"
                        wire:model="customer.setter_id"
                        option-value="id"
                        option-label="firstAndLastName"
                        options="setters"
                        name="customer.setter_id"
                        label="Setter"
                        noneOption
                        placeholder="Self Gen"/>
                </div>


                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.setter_fee" label="Setter Comission Rate" name="customer.setter_fee" disabled="{{!$customer->setter_id}}" atEnd="kW"/>
                </div>

                <div class="col-span-2 md:col-span-3" wire:ignore>
                    <x-select-searchable
                        x-on:popup-close="$wire.updatedCustomerSalesRepId"
                        wire:model="customer.sales_rep_id"
                        option-value="id"
                        option-label="firstAndLastName"
                        options="salesReps"
                        name="customer.sales_rep_id"
                        label="Sales Rep"
                        placeholder="{{$customer->sales_rep_id ? $salesRep->first_name . ' ' . $salesRep->last_name : 'Select an user'}}" />
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.sales_rep_fee" label="Sales Rep Pay Rate" name="customer.sales_rep_fee"  atEnd="kW" :disabled="user()->notHaveRoles(['Region Manager'])"/>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <x-input-currency  wire:model="customer.margin" label="Margin" name="customer.margin" readonly/>
                </div>

                <div class="col-span-2 md:col-span-2">
                    <x-input-currency wire:model="grossRepComission" label="Gross Rep Comission" name="grossRepComission" readonly/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.adders" label="Adders Total" name="customer.adders" step="0.01" type="number"/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="netRepComission" label="Net Rep Commisson" name="net_rep_commission" step="0.01" type="number" readonly/>
                </div>

                <div class="col-span-2">
                    <x-input wire:model="stockPoints" label="Stock Points" name="stockPoints" readonly/>
                </div>

                <div class="col-span-2 md:col-span-1 @if($customer->financer_id != 1) hidden @endif " wire:key="eniumPoints">
                    <x-input wire:model="customer.enium_points" label="Noble Pay Points" name="customer.enium_points" readonly/>
                </div>
            </div>

            <div class="mt-6 px-8 border-gray-200">
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
        </form>
    </div>
</div>
