<?php

use App\Models\User;

function loginThroughUi(User $user): void
{
    visit('/login')
        ->assertPathIs('/login')
        ->fill('#email', $user->email)
        ->fill('#password', 'password')
        ->press('Log in')
        ->assertPathIs('/');
}
