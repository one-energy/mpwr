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
                    @if (user()->role == "Admin" || user()->role == "Owner")
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
                            <x-input-calendar key="startDate" wire name="startDate" label="From" :value="$startDate"/>
                            <x-input-calendar key="finalDate" wire name="finalDate" label="From" :value="$finalDate"/>
                        </div>
                    @endif()
                </div>
            </div>
            <div class="grid justify-items-center mt-6 overflow-x-auto">
                <div class="flex flex-col rounded-md border border-gray-200">
                    <x-table>
                        <x-slot name="header">
                            <x-table.th-tr>
                                <x-table.th></x-table.th>
                                @if(user()->role == "Setter" || user()->role == "Sales Rep")
                                    <x-table.th by="setter_rate">
                                        @lang('My Setter Rate')
                                    </x-table.th>
                                @endif
                                @if(user()->role != "Setter")
                                    <x-table.th>
                                        @if (user()->role == "Admin" || user()->role == "Owner")
                                            @lang('PPW')
                                        @else
                                            @lang('My Closed PPW')
                                        @endif
                                    </x-table.th>
                                @endif
                                <x-table.th by="system_size">
                                    @if (user()->role == "Setter")
                                        @lang('My Set System Size (kW)')
                                    @endif
                                    @if (user()->role != "Setter")
                                        @if (user()->role == "Admin" || user()->role == "Owner")
                                            @lang('System Size (kW)')
                                        @else
                                            @lang('My Closed System Size (kW)')
                                        @endif
                                    @endif
                                </x-table.th>
                                <x-table.th by="my_setter_commission">
                                    @if(user()->role == "Admin" || user()->role == "Owner")
                                        @lang('Setter Comission')
                                    @else
                                        @lang('My Setter Comission')
                                    @endif
                                </x-table.th>
                                @if(user()->role != "Setter")
                                    <x-table.th by="my_closer_commission">
                                        @if (user()->role == "Admin" || user()->role == "Owner")
                                            @lang('Closer Comission')
                                        @else
                                            @lang('My Closer Comission')
                                        @endif
                                    </x-table.th>
                                @endif
                                @if(user()->role != "Setter" && user()->role != "Sales Rep")
                                    <x-table.th by="my_override_commission">
                                        @if (user()->role == "Admin" || user()->role == "Owner")
                                            @lang('Override Comission')
                                        @else
                                            @lang('My Override Comission')
                                        @endif
                                    </x-table.th>
                                @endif
                                <x-table.th by="my_recruter_commission">
                                    @if (user()->role == "Admin" || user()->role == "Owner")
                                        @lang('Recruter Comission')
                                    @else
                                        @lang('My Recruter Comission')
                                    @endif
                                </x-table.th>
                                <x-table.th by="my_total_commission">
                                    @if (user()->role == "Admin" || user()->role == "Owner")
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
                                @if(user()->role == "Setter" || user()->role == "Sales Rep")
                                    <x-table.td>
                                        {{ $this->formatNumber($customersOfUser?->avg('setter_fee')) }}
                                    </x-table.td>
                                @endif
                                @if(user()->role != "Setter")
                                    <x-table.th>
                                        {{ $this->formatNumber($this->getAvgSalesRepEpc($customersOfUser)) }}
                                    </x-table.th>
                                @endif
                                <x-table.td>
                                    {{ $this->formatNumber($this->getAvgSystemSize($customersOfUser)) }}
                                </x-table.td>
                                <x-table.td>
                                    {{ $this->formatNumber($this->getAvgSetterCommission($customersOfUser)) }}
                                </x-table.td>
                                @if(user()->role != "Setter")
                                    <x-table.td>
                                        {{ $this->formatNumber($this->getAvgSalesRepCommission($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                @if(user()->role != "Setter" && user()->role != "Sales Rep")
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
                                @if(user()->role == "Setter" || user()->role == "Sales Rep")
                                    <x-table.td class="font-bold">-</x-table.td>
                                @endif
                                @if(user()->role != "Setter")
                                    <x-table.td class="font-bold">-</x-table.td>
                                @endif
                                <x-table.td class="font-bold">
                                    {{ $this->formatNumber($customersOfUser->sum('system_size')) }}
                                </x-table.td>
                                <x-table.td class="font-bold">
                                    {{ $this->formatNumber($this->getSumSetterCommission($customersOfUser)) }}
                                </x-table.td>
                                @if(user()->role != "Setter")
                                    <x-table.td class="font-bold">
                                        {{ $this->formatNumber($this->getSumSalesRepCommission($customersOfUser)) }}
                                    </x-table.td>
                                @endif
                                @if(user()->role != "Setter" && user()->role != "Sales Rep")
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
                    @if (user()->role == "Office Manager" || user()->role == "Region Manager" || user()->role == "Department Manager")
                        <x-toggle wire:model="personalCustomers" class="items-end" label="Include Personal Sales"/>
                    @endif
                </div>
            </div>
            <div class="mt-6 overflow-x-auto ">
                <div class="flex flex-col">
                    <x-table :pagination="$customers->links()">
                        <x-slot name="header">
                            <x-table.th-tr>
                                <x-table.th by="home_owner">
                                    @lang('Home Owner')
                                </x-table.th>
                                <x-table.th by="sold_date">
                                    @lang('Sold Date')
                                </x-table.th>
                                <x-table.th by="setter">
                                    @lang('Setter')
                                </x-table.th>
                                <x-table.th by="setter_rate">
                                    @lang('Setter Rate')
                                </x-table.th>
                                <x-table.th by="closer">
                                    @lang('Closer')
                                </x-table.th>
                                @if(user()->role != "Setter")
                                    <x-table.th by="pay_rate">
                                        @lang('Pay Rate')
                                    </x-table.th>
                                    <x-table.th by="ppw">
                                        @lang('PPW')
                                    </x-table.th>
                                    <x-table.th by="adders">
                                        @lang('Adders')
                                    </x-table.th>
                                @endif
                                <x-table.th by="system_size">
                                    @lang('System Size')
                                </x-table.th>
                                <x-table.th by="setter_commission">
                                    @lang('Setter Commission')
                                </x-table.th>
                                @if(user()->role != "Setter")
                                    <x-table.th by="closer_commission">
                                        @lang('Closer Commission')
                                    </x-table.th>
                                    <x-table.th by="financing_type">
                                        @lang('Financing Type')
                                    </x-table.th>
                                    <x-table.th by="financer_type">
                                        @lang('Financer Type')
                                    </x-table.th>
                                    @if(user()->role != "Sales Rep")
                                        <x-table.th by="recruiter">
                                            @lang('Recruiter')
                                        </x-table.th>
                                        <x-table.th by="rec_rate">
                                            @lang('Rec Rate')
                                        </x-table.th>
                                        <x-table.th by="rec_ovr">
                                            @lang('Rec Ovr')
                                        </x-table.th>
                                        <x-table.th by="manager">
                                            @lang('Manager')
                                        </x-table.th>
                                        <x-table.th by="mgr_rate">
                                            @lang('Mgr Rate')
                                        </x-table.th>
                                        <x-table.th by="financer_type">
                                            @lang('Mgr Ovr')
                                        </x-table.th>
                                        @if(user()->role != "Office Manager")
                                            <x-table.th by="regional">
                                                @lang('Regional')
                                            </x-table.th>
                                            <x-table.th by="mgr_rate">
                                                @lang('RM Rate')
                                            </x-table.th>
                                            <x-table.th by="financer_type">
                                                @lang('RM Ovr')
                                            </x-table.th>
                                            @if(user()->role != "Region Manager")
                                                <x-table.th by="vp">
                                                    @lang('VP')
                                                </x-table.th>
                                                <x-table.th by="vp_rate">
                                                    @lang('VP Rate')
                                                </x-table.th>
                                                <x-table.th by="vp_type">
                                                    @lang('VP Ovr')
                                                </x-table.th>
                                                @if(user()->role != "Department Manager")
                                                    <x-table.th by="misc_one">
                                                        @lang('Misc 1')
                                                    </x-table.th>
                                                    <x-table.th by="mis_rate_one">
                                                        @lang('M1 Rate')
                                                    </x-table.th>
                                                    <x-table.th by="misc_override_one">
                                                        @lang('M1 OVR')
                                                    </x-table.th>
                                                    <x-table.th by="misc_one">
                                                        @lang('Misc 2')
                                                    </x-table.th>
                                                    <x-table.th by="mis_rate">
                                                        @lang('M2 Rate')
                                                    </x-table.th>
                                                    <x-table.th by="misc_override_one">
                                                        @lang('M2 OVR')
                                                    </x-table.th>
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
                                    <x-table.td>{{$customer->first_name}} {{$customer->last_name}}</x-table.td>
                                    <x-table.td>{{$customer->date_of_sale->format('M-d')}}</x-table.td>
                                    <x-table.td>{{$customer->userSetter?->first_name ?? '-'}} {{$customer->userSetter?->last_name}}</x-table.td>
                                    <x-table.td>{{$customer->setter_fee > 0 ? '$ ' . $customer->setter_fee : '-'}}</x-table.td>
                                    <x-table.td>{{$customer->userSalesRep?->first_name ?? '-'}} {{$customer->userSalesRep?->last_name}}</x-table.td>
                                    @if(user()->role != "Setter")
                                        <x-table.td>{{$customer->sales_rep_fee ? '$ ' . $customer->sales_rep_fee : '-'}}</x-table.td>
                                        <x-table.td>{{$customer->epc ? '$ ' . $customer->epc : '-'}}</x-table.td>
                                        <x-table.td>{{$customer->adders ? '$ ' . $customer->adders : '-'}}</x-table.td>
                                    @endif
                                    <x-table.td>{{$customer->system_size ?? '-'}}</x-table.td>
                                    <x-table.td>{{ $this->formatNumber($this->getSetterCommission($customer)) }}</x-table.td>
                                    @if(user()->role != "Setter")
                                        <x-table.td>{{ $this->formatNumber($this->getSalesRepCommission($customer)) }}</x-table.td>
                                        <x-table.td>{{$customer->financingtype?->name ?? '-'}}</x-table.td>
                                        <x-table.td>{{$customer->financer?->name ?? '-'}}</x-table.td>
                                        @if(user()->role != "Sales Rep")
                                            <x-table.td>{{$customer->recuiterOfSalesRep?->first_name ?? '-'}} {{$customer->recuiterOfSalesRep?->last_name}}</x-table.td>
                                            <x-table.td>{{$customer->recuiterOfSalesRep?->pay ?? '-'}}</x-table.td>
                                            <x-table.td>{{$customer->referral_override ?? '-'}}</x-table.td>
                                            <x-table.td>{{$customer->officeManager?->first_name ?? '-'}} {{$customer->officeManager?->last_name}}</x-table.td>
                                            <x-table.td>{{$customer->officeManager?->pay ?? '-'}}</x-table.td>
                                            <x-table.td>{{$customer->office_manager_override ?? '-'}}</x-table.td>
                                            @if(user()->role != "Office Manager")
                                                <x-table.td>{{$customer->regionManager?->first_name ?? '-'}} {{$customer->regionManager?->last_name}}</x-table.td>
                                                <x-table.td>{{$customer->regionManager?->pay ?? '-'}}</x-table.td>
                                                <x-table.td>{{$customer->region_manager_override ?? '-'}}</x-table.td>
                                                @if(user()->role != "Region Manager")
                                                    <x-table.td>{{$customer->departmentManager?->first_name ?? '-'}} {{$customer->departmentManager?->last_name}}</x-table.td>
                                                    <x-table.td>{{$customer->departmentManager?->pay ?? '-'}}</x-table.td>
                                                    <x-table.td>{{$customer->department_manager_override ?? '-'}}</x-table.td>
                                                    @if(user()->role != "Department Manager")
                                                        <x-table.td>{{$customer->payee_one ?? '-'}}</x-table.td>
                                                        <x-table.td>{{$customer->misc_override_one ?? '-'}}</x-table.td>
                                                        <x-table.td>{{($customer->misc_override_one * $customer->system_size) > 0 ? $customer->misc_override_one * $customer->system_size : '-'}}</x-table.td>
                                                        <x-table.td>{{$customer->payee_two ?? '-'}}</x-table.td>
                                                        <x-table.td>{{$customer->misc_override_two ?? '-'}}</x-table.td>
                                                        <x-table.td>{{($customer->misc_override_two * $customer->system_size) > 0 ? $customer->misc_override_two * $customer->system_size : '-'}}</x-table.td>
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
</div>
