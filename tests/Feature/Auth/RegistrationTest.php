<?php

namespace Tests\Feature\Auth;

use App\Enums\DocumentType;
use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Department;
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
        Department::factory()->create();
        City::factory()->create();
        Role::create(['name' => RoleEnum::CUSTOMER->value]);
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
            'document_type' => DocumentType::CC->value,
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'city_id' => City::query()->first()->id,
            'password' => 'Test_Password_0',
            'password_confirmation' => 'Test_Password_0',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
