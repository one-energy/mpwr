<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Models\Department;
use App\Traits\Livewire\FullTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Livewire\Component;

/**
 * @property-read bool $seeAllStatusSelected
 */
class ReportsOverview extends Component
{
    use FullTable;

    public Collection $customersOfUser;

    public Collection $customersOfSalesRepsRecruited;

    public array $ranges = Customer::RANGE_DATES;

    public array $status = Customer::STATUS;

    public string $selectedStatus = 'pending';

    public string $rangeType = 'year_to_date';

    public bool $personalCustomers = true;

    public int $departmentId;

    public $startDate = '';

    public $finalDate = '';

    public function mount()
    {
        $this->status = array_merge($this->status, [
            'all' => 'See all',
        ]);

        $this->sortBy        = 'date_of_sale';
        $this->sortDirection = 'desc';
        $this->departmentId  = Department::first()->id;

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $this->personalCustomers = false;
        }

        $this->startDate = Carbon::create($this->startDate)->firstOfYear()->startOfDay()->toString();
        $this->finalDate = Carbon::create($this->finalDate)->endOfDay()->toString();
    }

    public function render()
    {
        $this->getUserCustomers();

        return view('livewire.reports.reports-overview', [
            'departments' => Department::get(),
            'customers'   => Customer::query()
                ->joinInEachRelation()
                ->whereBetween('date_of_sale', [Carbon::create($this->startDate), Carbon::create($this->finalDate)])
                ->where(function ($query) {
                    $query->when($this->personalCustomers, function ($query) {
                        $query->orWhere('setter_id', user()->id)
                            ->orWhere('sales_rep_id', user()->id);
                    })
                        ->when(user()->hasRole('Office Manager'), function ($query) {
                            $query->orWhere('customers.office_manager_id', user()->id);
                        })
                        ->when(user()->hasRole('Region Manager'), function ($query) {
                            $query->orWhere('customers.region_manager_id', user()->id)
                                ->orWhere('customers.office_manager_id', user()->id);
                        })
                        ->when(user()->hasRole('Department Manager'), function ($query) {
                            $query->orWhere('customers.department_manager_id', user()->id)
                                ->orWhere('customers.region_manager_id', user()->id)
                                ->orWhere('customers.office_manager_id', user()->id);
                        })
                        ->when(user()->hasAnyRole(['Admin', 'Owner']),
                            function ($query) {
                                $query->whereHas('userSalesRep', function ($query) {
                                    $query->where('department_id', $this->departmentId);
                                });
                            });
                })
                ->when($this->installedStatus(), function ($query) {
                    $query->whereIsActive(true)
                        ->wherePanelSold(true);
                })
                ->when($this->pendingStatus(), function ($query) {
                    $query->whereIsActive(true)
                        ->wherePanelSold(false);
                })
                ->when($this->cancelledStatus(), function ($query) {
                    $query->whereIsActive(false);
                })
                ->with($this->getRelations())
                ->search($this->search)
                ->orderByRaw($this->sortBy . ' ' . $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }

    public function togglePaid(Customer $customer)
    {
        abort_if(user()->notHaveRoles(['Admin']), Response::HTTP_FORBIDDEN);

        $fields = [
            'panel_sold' => true,
            'paid_date'  => now(),
        ];

        if ($customer->panel_sold) {
            $fields = [
                'panel_sold' => false,
                'paid_date'  => null,
            ];
        }

        $customer->update($fields);
    }

    private function getRelations()
    {
        return [
            'financer',
            'userSetter',
            'userSalesRep',
            'financingType',
            'recruiterOfSalesRep',
            'officeManager',
            'regionManager',
            'departmentManager',
        ];
    }

    public function getUserCustomers()
    {
        $this->customersOfUser = Customer::where(function ($query) {
            $query->when(user()->notHaveRoles(['Admin', 'Owner']), function ($query) {
                $query->orWhere('setter_id', user()->id)
                    ->orWhere('sales_rep_id', user()->id);
            })
                ->when(user()->hasRole('Office Manager'), function ($query) {
                    $query->orWhere('office_manager_id', user()->id);
                })
                ->when(user()->hasRole('Region Manager'), function ($query) {
                    $query->orWhere('region_manager_id', user()->id)
                        ->orWhere('office_manager_id', user()->id);
                })
                ->when(user()->hasRole('Department Manager'), function ($query) {
                    $query->orWhere('department_manager_id', user()->id)
                        ->orWhere('region_manager_id', user()->id)
                        ->orWhere('office_manager_id', user()->id);
                })
                ->when(user()->hasAnyRole(['Admin', 'Owner']), function ($query) {
                    $query->whereHas('userSalesRep', function ($query) {
                        $query->where('department_id', $this->departmentId);
                    });
                });
        })
            ->with(['userSetter', 'userSalesRep'])
            ->whereBetween('date_of_sale', [Carbon::create($this->startDate), Carbon::create($this->finalDate)])
            ->when($this->installedStatus(), function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(true);
            })
            ->when($this->pendingStatus(), function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(false);
            })
            ->when($this->cancelledStatus(), function ($query) {
                $query->whereIsActive(false);
            })
            ->get();

        $this->customersOfSalesRepsRecruited = user()->customersOfSalesRepsRecruited()
            ->whereBetween('date_of_sale', [Carbon::create($this->startDate), Carbon::create($this->finalDate)])
            ->when($this->installedStatus(), function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(true);
            })
            ->when($this->pendingStatus(), function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(false);
            })
            ->when($this->cancelledStatus(), function ($query) {
                $query->whereIsActive(false);
            })
            ->get();
    }

    public function formatNumber(?float $value, int $decimals = 2, bool $currency = true)
    {
        if ($value === null) {
            return '-';
        }

        $newValue = $currency ? '$ ' . number_format($value, $decimals) : number_format($value, $decimals);

        return $value > 0 ? $newValue : '-';
    }

    public function getUserTotalCommission()
    {
        if (user()->hasRole('Setter')) {
            return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecruited) + $this->getSumSetterCommission($this->customersOfUser);
        }
        if (user()->hasRole('Sales Rep')) {
            return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecruited) + $this->getSumSetterCommission($this->customersOfUser) + $this->getSumSalesRepCommission($this->customersOfUser);
        }

        return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecruited) + $this->getSumSetterCommission($this->customersOfUser) + $this->getSumSalesRepCommission($this->customersOfUser) + $this->getSumOverrideCommission($this->customersOfUser);
    }

    public function getAvgSystemSize(Collection $customers)
    {
        return $customers->when(user()->hasRole('Setter'), function ($customer) {
            return $customer->where('setter_id', user()->id);
        })
            ->when(user()->notHaveRoles(['Setter', 'Admin', 'Owner']), function ($customer) {
                return $customer->where('sales_rep_id', user()->id);
            })
            ->when(user()->hasAnyRole(['Admin', 'Owner']), function ($customer) {
                return $customer->filter(function ($customer) {
                    return $customer->userSalesRep->department_id === $this->departmentId;
                });
            })
            ->avg('system_size');
    }

    public function getSumSetterCommission(Collection $customers)
    {
        return $customers
            ->when(user()->notHaveRoles(['Admin', 'Owner']), function (Collection $customers) {
                return $customers->where('setter_id', user()->id);
            })
            ->sum(fn($customer) => $this->getSetterCommission($customer));
    }

    public function getAvgSetterCommission(Collection $customers)
    {
        return $customers
            ->when(user()->notHaveRoles(['Admin', 'Owner']), function (Collection $customers) {
                return $customers->where('setter_id', user()->id);
            })
            ->avg(fn($customer) => $this->getSetterCommission($customer));
    }

    public function getSetterCommission(Customer $customer)
    {
        return $customer->setter_fee * ($customer->system_size * 1000);
    }

    public function getAvgSalesRepEpc(Collection $customers)
    {
        return $customers
            ->where('sales_rep_id', user()->id)
            ->avg('epc');
    }

    public function getSumSalesRepCommission(Collection $customers)
    {
        return $customers
            ->when(user()->notHaveRoles(['Admin', 'Owner']), function (Collection $customers) {
                return $customers->where('sales_rep_id', user()->id);
            })
            ->sum(fn($customer) => $this->getSalesRepCommission($customer));
    }

    public function getAvgSalesRepCommission(Collection $customers)
    {
        return $customers
            ->when(user()->notHaveRoles(['Admin', 'Owner']), function (Collection $customers) {
                return $customers->where('sales_rep_id', user()->id);
            })
            ->avg(fn($customer) => $this->getSalesRepCommission($customer));
    }

    public function getSalesRepCommission(Customer $customer)
    {
        return $customer->sales_rep_fee * ($customer->system_size * 1000);
    }

    public function getSumRecruiterCommission(Collection $customers)
    {
        return $customers->sum(function ($customer) {
            return $this->getRecruiterCommission($customer);
        });
    }

    public function getAvgRecruiterCommission(Collection $customers)
    {
        return $customers->avg(function ($customer) {
            return $this->getRecruiterCommission($customer);
        });
    }

    public function getRecruiterCommission(Customer $customer)
    {
        return $customer->referral_override * ($customer->system_size * 1000);
    }

    public function getAvgOverrideCommission(Collection $customers)
    {
        $customers = $this->filterCustomersWithOverrides($customers);

        return $customers?->avg(function ($customer) {
            return $this->getOverrideCommission($customer);
        });
    }

    public function getSumOverrideCommission(Collection $customers)
    {
        $customers = $this->filterCustomersWithOverrides($customers);

        return $customers?->sum(function ($customer) {
            return $this->getOverrideCommission($customer);
        });
    }

    public function getOverrideCommission(Customer $customer)
    {
        $overrideCommission = 0;
        if ($customer->office_manager_id == user()->id || user()->hasAnyRole(['Admin', 'Owner'])) {
            $overrideCommission += $customer->office_manager_override * ($customer->system_size * 1000);
        }
        if ($customer->region_manager_id == user()->id || user()->hasAnyRole(['Admin', 'Owner'])) {
            $overrideCommission += $customer->region_manager_override * ($customer->system_size * 1000);
        }
        if ($customer->department_manager_id == user()->id || user()->hasAnyRole(['Admin', 'Owner'])) {
            $overrideCommission += $customer->department_manager_override * ($customer->system_size * 1000);
        }

        return $overrideCommission;
    }

    public function filterCustomersWithOverrides(Collection $customers)
    {
        $departmentId = $this->departmentId;
        $customers    = $customers->filter(function ($customer) use ($departmentId) {
            if (user()->hasRole('Office Manager')) {
                return $customer->office_manager_id == user()->id;
            }
            if (user()->hasRole('Region Manager')) {
                return $customer->office_manager_id == user()->id || $customer->region_manager_id == user()->id;
            }
            if (user()->hasRole('Department Manager')) {
                return $customer->office_manager_id == user()->id || $customer->region_manager_id == user()->id || $customer->department_manager_id == user()->id;
            }
            if (user()->hasAnyRole(['Admin', 'Owner'])) {
                return $customer->userSalesRep->department_id == $departmentId;
            }
        });

        return $customers;
    }

    public function updatedRangeType($value)
    {
        $this->finalDate = Carbon::now()->endOfDay()->toString();
        switch ($value) {
            case 'today':
                $this->startDate = Carbon::now()->startOfDay()->toString();

                break;
            case 'week_to_date':
                $this->startDate = Carbon::now()->startOfWeek()->startOfDay()->toString();

                break;
            case 'last_week':
                $this->startDate = Carbon::now()->subWeek()->startOfWeek()->startOfDay()->toString();
                $this->finalDate = Carbon::now()->subWeek()->endOfWeek()->endOfDay()->toString();

                break;
            case 'month_to_date':
                $this->startDate = Carbon::now()->startOfMonth()->startOfDay()->toString();

                break;
            case 'last_month':
                $this->startDate = Carbon::now()->startOfMonth()->subMonth()->startOfDay()->toString();
                $this->finalDate = Carbon::now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->toString();

                break;
            case 'quarter_to_date':
                $this->startDate = Carbon::now()->startOfQuarter()->startOfDay()->toString();

                break;
            case 'last_quarter':
                $this->startDate = Carbon::now()->startOfQuarter()->subQuarter()->startOfDay()->toString();
                $this->finalDate = Carbon::now()->startOfQuarter()->subQuarter()->endOfQuarter()->endOfDay()->toString();

                break;
            case 'year_to_date':
                $this->startDate = Carbon::now()->startOfYear()->startOfDay()->toString();

                break;
            case 'last_year':
                $this->startDate = Carbon::now()->startOfYear()->subYear()->startOfDay()->toString();
                $this->finalDate = Carbon::now()->startOfYear()->subYear()->endOfYear()->endOfDay()->toString();

                break;
            case 'custom':
                $this->startDate = Carbon::create($this->startDate)->startOfDay()->toString();
                $this->finalDate = Carbon::create($this->finalDate)->endOfDay()->toString();

                break;
        }
    }

    public function getSumOfSystemSize($customersOfUser)
    {
        return $customersOfUser->sum('system_size');
    }

    public function sortBy()
    {
        return 'first_name';
    }

    private function cancelledStatus()
    {
        return $this->selectedStatus === 'canceled';
    }

    private function installedStatus()
    {
        return $this->selectedStatus === 'installed';
    }

    private function pendingStatus()
    {
        return $this->selectedStatus === 'pending';
    }

    public function getSeeAllStatusSelectedProperty()
    {
        return $this->selectedStatus === 'all';
    }

    public function statusColorFor(Customer $customer)
    {
        if (!$this->seeAllStatusSelected) {
            return '';
        }

        if ($this->canceled($customer)) {
            return 'text-red-500';
        }

        if ($this->pending($customer)) {
            return 'text-yellow-400';
        }

        if ($this->paid($customer)) {
            return 'text-green-base';
        }
    }

    private function canceled(Customer $customer)
    {
        return $customer->is_active === false;
    }

    private function pending(Customer $customer)
    {
        return $customer->is_active === true && $customer->panel_sold === false;
    }

    private function paid(Customer $customer)
    {
        return $customer->is_active === true && $customer->panel_sold === true;
    }
}
