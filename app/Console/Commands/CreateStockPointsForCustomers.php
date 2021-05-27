<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\StockPointsCalculationBases;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateStockPointsForCustomers extends Command
{
    protected $signature = 'create-stock-points-for-customers';

    protected $description = 'This command will create a stock points for customers';

    public function handle()
    {
        DB::transaction(function () {
            Customer::withTrashed()->each(function (Customer $customer) {
                if (!$customer->stockPoint()->exists()) {
                    $customer->stockPoint()->create([
                        'stock_recruiter'       => StockPointsCalculationBases::find(StockPointsCalculationBases::RECRUIT_ID)->stock_base_point,
                        'stock_setting'         => StockPointsCalculationBases::find(StockPointsCalculationBases::SETTING_ID)->stock_base_point,
                        'stock_personal_sale'   => StockPointsCalculationBases::find(StockPointsCalculationBases::PERSONAL_SALES_ID)->stock_base_point,
                        'stock_pod_leader_team' => StockPointsCalculationBases::find(StockPointsCalculationBases::POD_LEADER_TEAM_ID)->stock_base_point,
                        'stock_manager'         => StockPointsCalculationBases::find(StockPointsCalculationBases::OFFICE_MANAGER_ID)->stock_base_point,
                        'stock_divisional'      => StockPointsCalculationBases::find(StockPointsCalculationBases::DIVISIONAL_ID)->stock_base_point,
                        'stock_regional'        => StockPointsCalculationBases::find(StockPointsCalculationBases::REGIONAL_MANAGER_ID)->stock_base_point,
                        'stock_department'      => StockPointsCalculationBases::find(StockPointsCalculationBases::DEPARTMENT_ID)->stock_base_point,
                        'deleted_at'            => $customer->deleted_at,
                    ]);
                }
            });
        });
        return 0;
    }
}
