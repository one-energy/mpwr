<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="grid grid-cols-2 mt-5 gap-4">
                <div class="col-span-2 md:col-span-1 md:w-1/2">
                    <x-select wire:model="selectedStatus" name="pending_customer" labelInside>
                        @foreach($status as $key => $selectStatus)
                            <option value="{{$key}}">{{$selectStatus}}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="md:justify-self-end col-span-2 grid grid-cols-2 gap-2 md:col-span-1 md:w-1/2">
                    @if (user()->hasAnyRole(['Admin', 'Owner']))
                        <div class="col-span-2">
                            <x-select wire:model="departmentId" name="selectedDepartment">
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                    @endif
                    <x-select class="col-span-2" name="range_date" wire:model="rangeType" labelInside>
                        @foreach($ranges as $range)
                            <option value="{{$range['value']}}">{{$range['title']}}</option>
                        @endforeach
                    </x-select>
                    @if($rangeType == "custom")
                        <div class="grid grid-cols-2 gap-2 col-span-2 ">
                            <x-input-calendar wire label="From" name="startDate" />
                            <x-input-calendar wire label="To" name="finalDate" />
                        </div>
                    @endif
                </div>
            </div>
            <div class="grid justify-items-center mt-6 overflow-x-auto">
                <div class="flex flex-col rounded-md border border-gray-200">
                    <x-table>
                        <x-slot name="header">
                            <x-table.th-tr>
                                <x-table.th></x-table.th>
                                @if(user()->hasAnyRole(['Setter', 'Sales Rep']))
                                    <x-table.th by="setter_rate">
                                        @lang('My Setter Rate')
                                    </x-table.th>
                                @endif
                                @if(user()->role != "Setter")
                                    <x-table.th>
                                        @if (user()->hasAnyRole(['Admin', 'Owner']))
                                            @lang('PPW')
                                        @else
                                            @lang('My Closed PPW')
                                        @endif
                                    </x-table.th>
                                @endif
                                <x-table.th by="system_size">
                                    @if (user()->hasRole('Setter'))
                                        @lang('My Set System Size (kW)')
                                    @endif
                                    @if (user()->notHaveRoles(['Setter']))
                                        @if (user()->hasAnyRole(['Admin', 'Owner']))
                                            @lang('System Size (kW)')
                                        @else
                                            @lang('My Closed System Size (kW)')
                                        @endif
                                    @endif
                                </x-table.th>
                                <x-table.th by="my_setter_commission">
                                    @if (user()->hasAnyRole(['Admin', 'Owner']))
                                        @lang('Setter Comission')
                                    @else
                                        @lang('My Setter Comission')
                                    @endif
                                </x-table.th>
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.th by="my_closer_commission">
                                        @if (user()->hasAnyRole(['Admin', 'Owner']))
                                            @lang('Closer Comission')
                                        @else
                                            @lang('My Closer Comission')
                                        @endif
                                    </x-table.th>
                                @endif
                                @if(user()->notHaveRoles(['Setter', 'Sales Rep']))
                                    <x-table.th by="my_override_commission">
                                        @if (user()->hasAnyRole(['Admin', 'Owner']))
                                            @lang('Override Comission')
                                        @else
                                            @lang('My Override Comission')
                                        @endif
                                    </x-table.th>
                                @endif
                                <x-table.th by="my_recruter_commission">
                                    @if (user()->hasAnyRole(['Admin', 'Owner']))
                                        @lang('Recruter Comission')
                                    @else
                                        @lang('My Recruter Comission')
                                    @endif
                                </x-table.th>
                                <x-table.th by="my_total_commission">
                                    @if (user()->hasAnyRole(['Admin', 'Owner']))
                                        @lang('Total Comission')
                                    @else
                                        @lang('My Total Comission')
                                    @endif
                                </x-table.th>
                            </x-table.th-tr>
                        </x-slot>
                        <x-slot name="body">
                            <x-table.tr >
                                <x-table.td>Average</x-table.td>
                                @if(user()->hasAnyRole(['Setter', 'Sales Rep']))
                                    <x-table.td>
                                        {{ $this->formatNumber($customersOfUser?->avg('setter_fee')) }}
                                    </x-table.td>
                                @endif
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.td>
                                        {{ $this->formatNumber($this->getAvgSalesRepEpc($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                <x-table.td>
                                    {{ $this->formatNumber($this->getAvgSystemSize($customersOfUser), currency: false) }}
                                </x-table.td>
                                <x-table.td>
                                    {{ $this->formatNumber($this->getAvgSetterCommission($customersOfUser)) }}
                                </x-table.td>
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.td>
                                        {{ $this->formatNumber($this->getAvgSalesRepCommission($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                @if(user()->notHaveRoles(['Setter', 'Sales Rep']))
                                    <x-table.td>
                                        {{ $this->formatNumber($this->getAvgOverrideCommission($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                <x-table.td>
                                    {{ $this->formatNumber($this->getAvgRecruiterCommission($customersOfSalesRepsRecruited)) }}
                                </x-table.td>
                                <x-table.td>-</x-table.td>
                            </x-table.tr>
                            <x-table.tr class="bg-gray-100">
                                <x-table.td>Total</x-table.td>
                                @if(user()->hasAnyRole(['Setter', 'Sales Rep']))
                                    <x-table.td class="font-bold">-</x-table.td>
                                @endif
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.td class="font-bold">-</x-table.td>
                                @endif
                                <x-table.td class="font-bold">
                                    {{ $this->formatNumber($this->getSumOfSystemSize($customersOfUser), currency: false) }}
                                </x-table.td>
                                <x-table.td class="font-bold">
                                    {{ $this->formatNumber($this->getSumSetterCommission($customersOfUser)) }}
                                </x-table.td>
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.td class="font-bold">
                                        {{ $this->formatNumber($this->getSumSalesRepCommission($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                @if(user()->notHaveRoles(['Setter', 'Sales Rep']))
                                    <x-table.td>
                                        {{ $this->formatNumber($this->getSumOverrideCommission($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                <x-table.td class="font-bold">
                                    {{ $this->formatNumber($this->getSumRecruiterCommission($customersOfSalesRepsRecruited)) }}
                                </x-table.td>
                                <x-table.td class="font-bold">
                                    {{ $this->formatNumber($this->getUserTotalCommission()) }}
                                </x-table.td>
                            </x-table.tr>
                        </x-slot>
                    </x-table>
                </div>
            </div>
            <div class="mt-6">
                <x-search :search="$search"/>
                <div class="justify-items-end">
                    @if (user()->hasAnyRole(['Office Manager', 'Region Manager', 'Department Manager']))
                        <x-toggle wire:model="personalCustomers" class="items-end" label="Include Personal Sales"/>
                    @endif
                </div>
            </div>

            @if ($this->seeAllStatusSelected)
                <section class="mb-3 flex flex-row items-center w-full space-x-3 text-sm">
                    <div>
                        <span class="w-3 h-3 inline-block  bg-green-base"></span>
                        <span>Installed & Paid</span>
                    </div>
                    <div>
                        <span class="w-3 h-3 inline-block  bg-yellow-400"></span>
                        <span>Pending</span>
                    </div>
                    <div>
                        <span class="w-3 h-3 inline-block  bg-red-500"></span>
                        <span>Canceled</span>
                    </div>
                </section>
            @endif

            <div class="mt-6 flex flex-col">
                <x-table overflow-x :pagination="$customers->links()">
                    <x-slot name="header">
                        <x-table.th-tr>
                            @if (user()->hasRole('Admin'))
                                <x-table.th class="whitespace-no-wrap">
                                    @lang('Paid')
                                </x-table.th>
                            @endif
                            <x-table.th-searchable class="whitespace-no-wrap" by="CONCAT(customers.first_name, customers.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('Home Owner')
                            </x-table.th-searchable>
                            <x-table.th-searchable class="whitespace-no-wrap" by="date_of_sale" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('Sold Date')
                            </x-table.th-searchable>
                            <x-table.th-searchable by="CONCAT(setter.first_name, setter.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('Setter')
                            </x-table.th-searchable>
                            <x-table.th-searchable class="whitespace-no-wrap" by="setter_fee" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('Setter Rate')
                            </x-table.th-searchable>
                            <x-table.th-searchable by="CONCAT(salesRep.first_name, salesRep.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('Closer')
                            </x-table.th-searchable>
                            @if(user()->notHaveRoles(['Setter']))
                                <x-table.th-searchable class="whitespace-no-wrap" by="sales_rep_fee" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Pay Rate')
                                </x-table.th-searchable>
                                <x-table.th-searchable by="epc" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('PPW')
                                </x-table.th-searchable>
                                <x-table.th-searchable by="adders" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Adders')
                                </x-table.th-searchable>
                            @endif
                            <x-table.th-searchable class="whitespace-no-wrap" by="system_size" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('System Size')
                            </x-table.th-searchable>
                            <x-table.th-searchable class="whitespace-no-wrap" by="system_size * setter_fee" :sortedBy="$sortBy" :direction="$sortDirection">
                                @lang('Setter Commission')
                            </x-table.th-searchable>
                            @if(user()->notHaveRoles(['Setter']))
                                <x-table.th-searchable class="whitespace-no-wrap" by="system_size * sales_rep_fee" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Closer Commission')
                                </x-table.th-searchable >
                                <x-table.th-searchable class="whitespace-no-wrap" by="financings.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Financing Type')
                                </x-table.th-searchable>
                                <x-table.th-searchable class="whitespace-no-wrap" by="financers.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                    @lang('Financer Type')
                                </x-table.th-searchable>
                                @if(user()->notHaveRoles(['Sales Rep']))
                                    <x-table.th-searchable by="CONCAT(recruiter.first_name, recruiter.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Recruiter')
                                    </x-table.th-searchable>
                                    <x-table.th-searchable class="whitespace-no-wrap" by="recruiter.pay" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Rec Rate')
                                    </x-table.th-searchable>
                                    <x-table.th-searchable class="whitespace-no-wrap" by="referral_override" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Rec Ovr')
                                    </x-table.th-searchable>
                                    <x-table.th-searchable by="CONCAT(manager.first_name, manager.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Manager')
                                    </x-table.th-searchable>
                                    <x-table.th-searchable class="whitespace-no-wrap" by="manager.pay" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Mgr Rate')
                                    </x-table.th-searchable>
                                    <x-table.th-searchable class="whitespace-no-wrap" by="office_manager_override" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Mgr Ovr')
                                    </x-table.th-searchable>
                                    @if(user()->notHaveRoles(['Office Manager']))
                                        <x-table.th-searchable by="CONCAT(regionManager.first_name, regionManager.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Regional')
                                        </x-table.th-searchable>
                                        <x-table.th-searchable class="whitespace-no-wrap" by="regionManager.pay" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('RM Rate')
                                        </x-table.th-searchable>
                                        <x-table.th-searchable class="whitespace-no-wrap" by="region_manager_override" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('RM Ovr')
                                        </x-table.th-searchable>
                                        @if(user()->notHaveRoles(['Region Manager']))
                                            <x-table.th-searchable by="CONCAT(departmentManager.first_name, departmentManager.last_name)" :sortedBy="$sortBy" :direction="$sortDirection">
                                                @lang('VP')
                                            </x-table.th-searchable>
                                            <x-table.th-searchable class="whitespace-no-wrap" by="departmentManager.pay" :sortedBy="$sortBy" :direction="$sortDirection">
                                                @lang('VP Rate')
                                            </x-table.th-searchable>
                                            <x-table.th-searchable class="whitespace-no-wrap" by="department_manager_override" :sortedBy="$sortBy" :direction="$sortDirection">
                                                @lang('VP Ovr')
                                            </x-table.th-searchable>
                                            @if(user()->notHaveRoles(['Department Manager']))
                                                <x-table.th-searchable class="whitespace-no-wrap" by="customers.payee_one" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Misc 1')
                                                </x-table.th-searchable>
                                                <x-table.th-searchable class="whitespace-no-wrap" by="customers.misc_override_one" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('M1 Rate')
                                                </x-table.th-searchable>
                                                <x-table.th-searchable class="whitespace-no-wrap" by="customers.misc_override_one * system_size" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('M1 OVR')
                                                </x-table.th-searchable>
                                                <x-table.th-searchable class="whitespace-no-wrap" by="customers.payee_two" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Misc 2')
                                                </x-table.th-searchable>
                                                <x-table.th-searchable class="whitespace-no-wrap" by="customers.misc_override_two" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('M2 Rate')
                                                </x-table.th-searchable>
                                                <x-table.th-searchable class="whitespace-no-wrap" by="customers.misc_override_two * system_size" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('M2 OVR')
                                                </x-table.th-searchable>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </x-table.th-tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($customers as $customer)
                            <x-table.tr >
                                @if (user()->hasRole('Admin'))
                                    <x-table.td>
                                        <input
                                            class="form-checkbox text-green-base transition duration-150 ease-in-out"
                                            type="checkbox"
                                            {{ $customer->panel_sold ? 'checked' : '' }}
                                            wire:key="customer-{{ $customer->id }}"
                                            wire:click="togglePaid({{ $customer->id }})"
                                        >
                                    </x-table.td>
                                @endif
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{$customer->full_name}}
                                </x-table.td>
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{$customer->date_of_sale->format('M-d')}}
                                </x-table.td>
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{$customer->userSetter?->first_name ?? '-'}} {{$customer->userSetter?->last_name}}
                                </x-table.td>
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{$customer->setter_fee > 0 ? '$ ' . $customer->setter_fee : '-'}}
                                </x-table.td>
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{$customer->userSalesRep?->first_name ?? '-'}} {{$customer->userSalesRep?->last_name}}
                                </x-table.td>
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                        {{$customer->sales_rep_fee ? '$ ' . $customer->sales_rep_fee : '-'}}
                                    </x-table.td>
                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                        {{$customer->epc ? '$ ' . $customer->epc : '-'}}
                                    </x-table.td>
                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                        {{$customer->adders ? '$ ' . $customer->adders : '-'}}
                                    </x-table.td>
                                @endif
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{$customer->system_size ?? '-'}}
                                </x-table.td>
                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                    {{ $this->formatNumber($this->getSetterCommission($customer)) }}
                                </x-table.td>
                                @if(user()->notHaveRoles(['Setter']))
                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                        {{ $this->formatNumber($this->getSalesRepCommission($customer)) }}
                                    </x-table.td>
                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                        {{$customer->financingType?->name ?? '-'}}
                                    </x-table.td>
                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                        {{$customer->financer?->name ?? '-'}}
                                    </x-table.td>
                                    @if(user()->notHaveRoles(['Sales Rep']))
                                        <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                            {{$customer->recruiterOfSalesRep?->first_name ?? '-'}} {{$customer->recruiterOfSalesRep?->last_name}}
                                        </x-table.td>
                                        <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                            {{$customer->recruiterOfSalesRep?->pay ?? '-'}}
                                        </x-table.td>
                                        <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                            {{$customer->referral_override ?? '-'}}
                                        </x-table.td>
                                        <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                            {{$customer->officeManager?->first_name ?? '-'}} {{$customer->officeManager?->last_name}}
                                        </x-table.td>
                                        <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                            {{$customer->officeManager?->pay ?? '-'}}
                                        </x-table.td>
                                        <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                            {{$customer->office_manager_override ?? '-'}}
                                        </x-table.td>
                                        @if(user()->notHaveRoles(['Office Manager']))
                                            <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                {{$customer->regionManager?->first_name ?? '-'}} {{$customer->regionManager?->last_name}}
                                            </x-table.td>
                                            <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                {{$customer->regionManager?->pay ?? '-'}}
                                            </x-table.td>
                                            <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                {{$customer->region_manager_override ?? '-'}}
                                            </x-table.td>
                                            @if(user()->notHaveRoles(['Region Manager']))
                                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                    {{$customer->departmentManager?->first_name ?? '-'}} {{$customer->departmentManager?->last_name}}
                                                </x-table.td>
                                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                    {{$customer->departmentManager?->pay ?? '-'}}
                                                </x-table.td>
                                                <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                    {{$customer->department_manager_override ?? '-'}}
                                                </x-table.td>
                                                @if(user()->notHaveRoles(['Department Manager']))
                                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                        {{$customer->payee_one ?? '-'}}
                                                    </x-table.td>
                                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                        {{$customer->misc_override_one ?? '-'}}
                                                    </x-table.td>
                                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                        {{($customer->misc_override_one * $customer->system_size) > 0 ? $customer->misc_override_one * $customer->system_size : '-'}}
                                                    </x-table.td>
                                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                        {{$customer->payee_two ?? '-'}}
                                                    </x-table.td>
                                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                        {{$customer->misc_override_two ?? '-'}}
                                                    </x-table.td>
                                                    <x-table.td class="{{ $this->statusColorFor($customer) }}">
                                                        {{($customer->misc_override_two * $customer->system_size) > 0 ? $customer->misc_override_two * $customer->system_size : '-'}}
                                                    </x-table.td>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </x-table.tr>
                        @endforeach
                    </x-slot>
                </x-table>
            </div>
        </div>
    </div>
</div>
