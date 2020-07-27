<?php

namespace Tests\Feature\Models;

use Tests\TestCase;

class AuditTest extends TestCase
{    
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
