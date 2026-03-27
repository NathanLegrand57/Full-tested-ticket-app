<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('hides admin create actions for a standard user', function (): void {
    $user = User::factory()->create();

    loginThroughUi($user);

    visit('/')
        ->assertPathIs('/')
        ->assertDontSee('Créer un spectacle');
});

it('hides admin update actions for a standard user', function (): void {
    $user = User::factory()->create();

    loginThroughUi($user);

    visit('/')
        ->assertPathIs('/')
        ->assertDontSee('Éditer');
});

it('hides admin delete actions for a standard user', function (): void {
    $user = User::factory()->create();

    loginThroughUi($user);

    visit('/')
        ->assertPathIs('/')
        ->assertDontSee('Supprimer');
});

it('allows admin stats for user with permission', function (): void {
    $user = User::factory()->create();
    Permission::findOrCreate('admin-stats');
    $user->givePermissionTo('admin-stats');

    loginThroughUi($user);

    visit('/admin/stats')
        ->assertPathIs('/admin/stats')
        ->assertSee('Statistiques des ventes')
        ->assertSee('Billets vendus');
});
