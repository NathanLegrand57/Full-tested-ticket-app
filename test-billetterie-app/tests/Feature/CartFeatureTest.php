<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test cart can be created and relates to user and show.
     */
    public function test_cart_creation_and_relationships(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create();

        $cart = Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 3,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 3,
        ]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertInstanceOf(Show::class, $cart->show);
        $this->assertEquals($user->id, $cart->user->id);
        $this->assertEquals($show->id, $cart->show->id);
    }
}
