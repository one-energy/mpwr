<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Models\Department;
use App\Traits\Livewire\FullTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ReportsOverview extends Component
{
    use FullTable;

    public Collection $customersOfUser;

    public Collection $customersOfSalesRepsRecuited;

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
        $this->departmentId = Department::first()->id;
        if (user()->role == 'Admin' || user()->role == 'Owner') {
            $this->personalCustomers = false;
        }
        $this->startDate = Carbon::create($this->startDate)->firstOfYear()->startOfDay()->toString();
        $this->finalDate = Carbon::create($this->finalDate)->endOfDay()->toString();
    }

    public function render()
    {
        $departmentId = $this->departmentId;
        $this->getUserCustomers();

        return view('livewire.reports.reports-overview', [
            'departments' => Department::get(),
            'customers'   => Customer::query()
                ->whereBetween('date_of_sale', [Carbon::create($this->startDate), Carbon::create($this->finalDate)])
                ->where(function ($query) use ($departmentId) {
                    $query->when($this->personalCustomers, function ($query) {
                        $query->orWhere('setter_id', user()->id)
                            ->orWhere('sales_rep_id', user()->id);
                    })
                        ->when(user()->role == 'Office Manager', function ($query) {
                            $query->orWhere('office_manager_id', user()->id);
                        })
                        ->when(user()->role == 'Region Manager', function ($query) {
                            $query->orWhere('region_manager_id', user()->id)
                                ->orWhere('office_manager_id', user()->id);
                        })
                        ->when(user()->role == 'Department Manager', function ($query) {
                            $query->orWhere('department_manager_id', user()->id)
                                ->orWhere('region_manager_id', user()->id)
                                ->orWhere('office_manager_id', user()->id);
                        })
                        ->when(user()->role == 'Admin' || user()->role == 'Owner',
                            function ($query) use ($departmentId) {
                                $query->whereHas('userSalesRep', function ($query) use ($departmentId) {
                                    $query->where('department_id', $departmentId);
                                });
                            });
                })
                ->when($this->selectedStatus == 'installed', function ($query) {
                    $query->whereIsActive(true)
                        ->wherePanelSold(true);
                })
                ->when($this->selectedStatus == 'pending', function ($query) {
                    $query->whereIsActive(true)
                        ->wherePanelSold(false);
                })
                ->when($this->selectedStatus == 'canceled', function ($query) {
                    $query->whereIsActive(false);
                })
                ->search($this->search)
                ->paginate($this->perPage),
        ]);
    }

    public function getUserCustomers()
    {
        $departmentId          = $this->departmentId;
        $this->customersOfUser = Customer::where(function ($query) use ($departmentId) {
            $query->when(user()->role != 'Admin' && user()->role != 'Owner', function ($query) {
                $query->orWhere('setter_id', user()->id)
                    ->orWhere('sales_rep_id', user()->id);
            })
                ->when(user()->role == 'Office Manager', function ($query) {
                    $query->orWhere('office_manager_id', user()->id);
                })
                ->when(user()->role == 'Region Manager', function ($query) {
                    $query->orWhere('region_manager_id', user()->id)
                        ->orWhere('office_manager_id', user()->id);
                })
                ->when(user()->role == 'Department Manager', function ($query) {
                    $query->orWhere('department_manager_id', user()->id)
                        ->orWhere('region_manager_id', user()->id)
                        ->orWhere('office_manager_id', user()->id);
                })
                ->when(user()->role == 'Admin' || user()->role == 'Owner', function ($query) use ($departmentId) {
                    $query->whereHas('userSalesRep', function ($query) use ($departmentId) {
                        $query->where('department_id', $departmentId);
                    });
                });
        })
            ->whereBetween('date_of_sale', [Carbon::create($this->startDate), Carbon::create($this->finalDate)])
            ->when($this->selectedStatus == 'installed', function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(true);
            })
            ->when($this->selectedStatus == 'pending', function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(false);
            })
            ->when($this->selectedStatus == 'canceled', function ($query) {
                $query->whereIsActive(false);
            })
            ->get();

        $this->customersOfSalesRepsRecuited = user()->customersOfSalesRepsRecuited()
            ->whereBetween('date_of_sale', [Carbon::create($this->startDate), Carbon::create($this->finalDate)])
            ->when($this->selectedStatus == 'installed', function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(true);
            })
            ->when($this->selectedStatus == 'pending', function ($query) {
                $query->whereIsActive(true)
                    ->wherePanelSold(false);
            })
            ->when($this->selectedStatus == 'canceled', function ($query) {
                $query->whereIsActive(false);
            })
            ->get();
    }

    public function formatNumber(?int $value, int $decimals = 2)
    {
        if ($value === null) {
            return '-';
        }

        return $value > 0 ? '$ ' . number_format($value, $decimals) : '-';
    }

    public function getUserTotalCommission()
    {
        if (user()->role == 'Setter') {
            return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecuited) + $this->getSumSetterCommission($this->customersOfUser);
        }
        if (user()->role == 'Sales Rep') {
            return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecuited) + $this->getSumSetterCommission($this->customersOfUser) + $this->getSumSalesRepCommission($this->customersOfUser);
        }

        return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecuited) + $this->getSumSetterCommission($this->customersOfUser) + $this->getSumSalesRepCommission($this->customersOfUser) + $this->getSumOverrideCommission($this->customersOfUser);
    }

    public function getAvgSystemSize(Collection $customers)
    {
        $departmentId = $this->departmentId;

        $customers->when(user()->role == 'Setter', function ($query) {
            $query->where('setter_id', user()->id);
        })
            ->when(user()->role != 'Setter' && user()->role != 'Admin' && user()->role != 'Owner', function ($query) {
                $query->where('sales_rep_id', user()->id);
            })
            ->when(user()->role == 'Admin' || user()->role == 'Owner', function ($query) use ($departmentId) {
                $query->filter(function ($customer) use ($departmentId) {
                    return $customer->userSalesRep->department_id == $departmentId;
                });
            });

        return $customers->avg('system_size');
    }

    public function getSumSetterCommission(Collection $customers)
    {
        $customers->where('setter_id', user()->id);

        return $customers->sum(function ($customer) {
            return $this->getSetterCommission($customer);
        });
    }

    public function getAvgSetterCommission(Collection $customers)
    {
        $customers->where('setter_id', user()->id);

        return $customers->avg(function ($customer) {
            return $this->getSetterCommission($customer);
        });
    }

    public function getSetterCommission(Customer $customer)
    {
        return $customer->setter_fee * ($customer->system_size * 1000);
    }

    public function getAvgSalesRepEpc(Collection $customers)
    {
        $customers->where('sales_rep_id', user()->id);

        return $customers->avg('epc');
    }

    public function getSumSalesRepCommission(Collection $customers)
    {
        $customers->where('sales_rep_id', user()->id);

        return $customers->sum(function ($customer) {
            return $this->getSalesRepCommission($customer);
        });
    }

    public function getAvgSalesRepCommission(Collection $customers)
    {
        $customers->where('sales_rep_id', user()->id);

        return $customers->avg(function ($customer) {
            return $this->getSalesRepCommission($customer);
        });
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
            return $customer->office_manager_override ?? 0;
        });
    }

    public function getSumOverrideCommission(Collection $customers)
    {
        $customers = $this->filterCustomersWithOverrides($customers);

        return $customers?->sum(function ($customer) {
            return $customer->office_manager_override ?? 0;
        });
    }

    public function filterCustomersWithOverrides(Collection $customers)
    {
        $departmentId = $this->departmentId;
        $customers->filter(function ($customer) use ($departmentId) {
            if (user()->role == 'Office Manager') {
                return $customer->office_manager_id == user()->id;
            }
            if (user()->role == 'Region Manager') {
                return $customer->office_manager_id == user()->id || $customer->region_manager_id == user()->id;
            }
            if (user()->role == 'Department Manager') {
                return $customer->office_manager_id == user()->id || $customer->region_manager_id == user()->id || $customer->department_manager_id == user()->id;
            }
            if (user()->role == 'Admin' || user()->role == 'Owner') {
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

    public function sortBy()
    {
        return 'first_name';
    }
}
