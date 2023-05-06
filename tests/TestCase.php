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
        Storage::disk('product_images')->makeDirectory('');
    }

    protected function tearDown(): void
    {
        Storage::disk('product_images')->deleteDirectory('');
        parent::tearDown();
    }
}
