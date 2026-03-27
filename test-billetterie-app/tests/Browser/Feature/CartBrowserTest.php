<?php

use App\Models\Show;
use App\Models\User;

it('adds a show to cart from the show page', function (): void {
    $user = User::factory()->create();
    $show = Show::factory()->create([
        'title' => 'Concert Test Browser',
    ]);

    loginThroughUi($user);

    visit('/shows/' . $show->id)
        ->assertPathIs('/shows/' . $show->id)
        ->fill('#quantity', '2')
        ->press('🛒 Ajouter au panier')
        ->assertPathIs('/cart')
        ->assertSee('Billet ajouté au panier')
        ->assertSee('Concert Test Browser');

    $this->assertDatabaseHas('carts', [
        'user_id' => $user->id,
        'show_id' => $show->id,
        'quantity' => 2,
    ]);
});
