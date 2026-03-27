<?php

use App\Models\Cart;
use App\Models\Show;
use App\Models\User;

it('redirects checkout to cart when the cart is empty', function (): void {
    $user = User::factory()->create();

    loginThroughUi($user);

    visit('/payment')
        ->assertPathIs('/cart')
        ->assertSee('Votre panier est vide');
});

it('shows checkout summary when the cart has items', function (): void {
    $user = User::factory()->create();
    $show = Show::factory()->create([
        'title' => 'Festival Checkout',
        'price' => 49.99,
    ]);

    Cart::create([
        'user_id' => $user->id,
        'show_id' => $show->id,
        'quantity' => 2,
    ]);

    loginThroughUi($user);

    visit('/payment')
        ->assertPathIs('/payment')
        ->assertSee('Informations de paiement')
        ->assertSee('Festival Checkout')
        ->assertSee('TOTAL:');
});
