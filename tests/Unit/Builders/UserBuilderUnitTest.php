<?php

namespace Tests\Unit\Builders;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBuilderUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_search(): void
    {
        User::factory(5)->create();

        $expected = User::all();
        $test = User::query()->contains(null, [])->get();
        $this->assertEquals($expected, $test);

        $expected = User::query()->where('email', 'like', '%d%')->get();
        $test = User::query()->contains('l', ['email'])->get();

        for ($i = 0; $i < count($expected); $i++) {
            $this->assertEquals($expected[$i]->email, $test[$i]->email);
        }
    }

    public function test_list_users_without_an_specific_user(): void
    {
        $user = User::factory()->create();
        User::factory(5)->create();

        $test = User::query()->withoutUser($user->id)->get();

        $this->assertNotContains($user, $test);
    }
}
