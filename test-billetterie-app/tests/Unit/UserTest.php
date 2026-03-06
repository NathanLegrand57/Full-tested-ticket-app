<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test the getNameAttribute accessor.
     */
    public function test_get_name_attribute(): void
    {
        $user = new User([
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $user->name);
    }

    /**
     * Test user fillable attributes.
     */
    public function test_user_fillable_attributes(): void
    {
        $user = new User([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '123456789',
            'password' => 'password',
        ]);

        $this->assertEquals('Jane', $user->firstname);
        $this->assertEquals('Smith', $user->lastname);
        $this->assertEquals('jane@example.com', $user->email);
    }
}