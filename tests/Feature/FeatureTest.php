<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class FeatureTest extends TestCase
{
    use WithFaker, DatabaseMigrations;
}
