<div>
    <div class="max-w-6xl py-5 mx-auto px-6 lg:px-8">
        <x-link :href="route('home')" color="gray" class="inline-flex items-center text-sm font-medium leading-5 border-b-2 border-green-base hover:border-green-500">
            <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Dashboard')
        </x-link>
    </div>
    <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <form wire:submit.prevent="store">
            <div class="grid grid-cols-2 gap-4 sm:col-gap-4 md:grid-cols-6">
                @if(user()->role == 'Admin' || user()->role == 'Owner')
                <div class="col-span-2 md:col-span-6">
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
                    <x-input-calendar wire:model="customer.date_of_sale" label="Date of Sale" name="customer.date_of_sale"/>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <x-input-add-on wire:model="customer.system_size" label="System Size" name="system_size" addOn="kW" name="customer.system_size"/>
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
                    <x-select wire:model="customer.financing_id" label="Financing" name="customer.financing_id">
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
                    <div class="col-span-1 md:col-span-1 md:col-start-4">
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
                @endif

                @if($customer->financer_id == 1)
                    <div class="col-span-1 md:col-span-2">
                        <x-select wire:model="customer.term_id" label="Term" name="customer.term_id" readonly>
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

                <div class="col-span-2 md:col-span-6">
                    <x-input-currency wire:model="customer.epc" label="EPC" name="customer.epc" observation="Sold Price"/>
                </div>

                <div class="col-span-2 md:col-span-3 md:col-start-1">
                    <x-select wire:change="getSetterRate($event.target.value)" wire:model="customer.setter_id" label="Setter" name="customer.setter_id">
                        <option wire:click="setSelfGen" value="">Self Gen</option>
                        @foreach($users as $setter)
                            @if($setter->role == 'Setter')
                                <option value="{{$setter->id}}">{{$setter->first_name}} {{$setter->last_name}}</option>
                            @endif
                        @endforeach
                    </x-select>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.setter_fee" label="Setter Comission Rate" name="customer.setter_fee" disabled="{{!$customer->setter_id}}"/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-select wire:change="getSalesRepRate($event.target.value)" wire:model="customer.sales_rep_id" label="Sales Rep" name="customer.sales_rep_id">
                        <option value="">None</option>
                        @foreach($users as $rep)
                            <option value="{{$rep->id}}">{{$rep->first_name}} {{$rep->last_name}}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.sales_rep_fee" label="Sales Rep Pay Rate" name="customer.sales_rep_fee" readonly/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-select-searchable
                        wire:change="getSalesRepRate($event.target.value)"
                        wire:model="customer.sales_rep_id"
                        option-value="id"
                        option-label="twoNames"
                        options="salesReps"
                        name="customer.sales_rep_id"
                        label="Sales Rep"
                        placeholder="{{user()->first_name}} {{user()->last_name}}" />
                </div>

                <div class="col-span-2 md:col-span-1">
                    <x-input-currency  wire:model="customer.margin" label="Margin" name="customer.margin" readonly/>
                </div>

                <div class="col-span-2 md:col-span-2">
                    <x-input-currency wire:model="grossRepComission" label="Gross Rep Comission" name="grossRepComission" type="number" readonly/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.adders" label="Adders Total" name="customer.adders" step="0.01" type="number"/>
                </div>

                <div class="col-span-2 md:col-span-3">
                    <x-input-currency wire:model="customer.sales_rep_comission" label="Net Rep Commisson" name="customer.sales_rep_comission" step="0.01" type="number" readonly/>
                </div>

                <div class="col-span-2">
                    <x-input wire:model="stockPoints" label="Stock Points" name="stockPoints" readonly/>
                </div>

                @if($customer->financer_id == 1)
                    <div class="col-span-2 md:col-span-1">
                        <x-input wire:model="customer.enium_points" label="Noble Pay Points" name="customer.enium_points" readonly/>
                    </div>
                @endif
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
        </form>
    </div>
</div>
