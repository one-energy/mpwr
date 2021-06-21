<?php

namespace Tests\Unit\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\TestCase;

class CustomerCalculationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_calculate_comission()
    {
        $customer                = new Customer();
        $customer->system_size   = 4.5;
        $customer->adders        = 300;
        $customer->epc           = 4.7;
        $customer->setter_fee    = 0.2;
        $customer->sales_rep_fee = 3.1;
        $customer->calcComission();

        $this->assertEquals($customer->sales_rep_comission, 13950.00);
    }

    /** @test */
    public function it_should_calculate_margin()
    {
        $customer                = new Customer();
        $customer->epc           = 6.5;
        $customer->setter_fee    = 2.3;
        $customer->sales_rep_fee = 2.6;
        
        $customer->calcMargin();

        $this->assertEquals($customer->margin, 1.6);
    }
}
