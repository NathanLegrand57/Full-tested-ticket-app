<?php

use App\Models\User;

it('loads the login page in browser', function (): void {
    visit('/login')
        ->assertPathIs('/login')
        ->assertSee('Log in')
        ->assertVisible('#email')
        ->assertVisible('#password');
});

it('put wrong credentials in login form', function (): void {
    visit('/login')
        ->assertPathIs('/login')
        ->fill('#email', 'wrong@example.com')
        ->fill('#password', 'wrongpassword')
        ->press('Log in')
        ->assertSee('These credentials do not match our records.');
});

it('logs in with valid credentials and redirects to shows page', function (): void {
    $user = User::factory()->create([
        'email' => 'valid.user@example.com',
        'password' => bcrypt('password123'),
    ]);

    visit('/login')
        ->assertPathIs('/login')
        ->fill('#email', $user->email)
        ->fill('#password', 'password123')
        ->press('Log in')
        ->assertPathIs('/')
        ->assertDontSee('These credentials do not match our records.');
});
