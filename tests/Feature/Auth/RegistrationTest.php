<?php

namespace Tests\Feature\Auth;

use App\Enums\DocumentType;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        DB::unprepared(
            'INSERT INTO departments (id, name) VALUES (1, "Test State")'
        );
        DB::unprepared(
            'INSERT INTO cities (id, name, department_id) VALUES (1, "Test City", 1)'
        );
        Role::create(['name' => 'Customer']);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'surname' => 'Test User',
            'document' => '12345678',
            'document_type' => DocumentType::ID->value,
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'city_id' => '1',
            'password' => 'Test_Password_0',
            'password_confirmation' => 'Test_Password_0',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
