<?php

namespace Tests\Feature\Migration\DailyNumber;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AddOfficeIdColumnTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_add_office_id_daily_numbers_table()
    {
        $this->assertTrue(Schema::hasColumn('daily_numbers', 'office_id'));
    }
}
