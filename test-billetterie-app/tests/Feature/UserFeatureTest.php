<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can be created with fillable attributes.
     */
    public function test_user_creation(): void
    {
        $user = User::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '123456789',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('users', [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane@example.com',
        ]);

        $this->assertEquals('Jane Smith', $user->name);
    }
}
