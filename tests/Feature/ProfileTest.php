<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Models\City;
use App\Models\Customer;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();
        Department::factory(1)->create();
        City::factory(1)->create();
    }

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->put('/profile', [
                'name' => 'Test User',
                'surname' => 'Test Surname',
                'document_type' => DocumentType::ID->value,
                'document' => '12345678',
                'email' => 'test@example.com',
                'address' => 'Test Address',
                'city_id' => City::query()->first()->id,
                'phone' => '1234567890',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $customer->refresh();

        $this->assertSame('Test User', $customer->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(
    ): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->put('/profile', [
                'name' => 'Test User',
                'surname' => 'Test Surname',
                'document_type' => DocumentType::ID->value,
                'document' => '12345678',
                'email' => $user->email,
                'address' => 'Test Address',
                'city_id' => City::query()->first()->id,
                'phone' => '1234567890',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNotNull($user->deleted_at);
    }

    public function test_correct_password_must_be_provided_to_delete_account(
    ): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
