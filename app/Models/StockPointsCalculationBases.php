<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPointsCalculationBases extends Model
{
    use HasFactory;

    const RECRUIT_ID          = 1;
    const SETTING_ID          = 2;
    const PERSONAL_SALES_ID   = 3;
    const POD_LEADER_TEAM_ID  = 4;
    const OFFICE_MANAGER_ID   = 5;
    const DIVISIONAL_ID       = 6;
    const REGIONAL_MANAGER_ID = 7;
    const DEPARTMENT_ID       = 8;
    const YEAR_MULTIPLIER_ID  = 9;
}
