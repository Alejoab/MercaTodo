<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('product_images');
        Storage::fake('exports');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
