<?php

namespace Tests\Feature\Incentive;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncentiveTest extends TestCase
{
    /** @test */
    public function it_should_show_incentives()
    {
        $response = $this->get('/incentives');

        $response->assertStatus(200);
    }
}
