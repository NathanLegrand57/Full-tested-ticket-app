<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index page shows current user cart items.
     */
    public function test_index_shows_user_cart_items(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create(['price' => 20]);

        Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 2
        ]);

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertViewHas('cartItems');
        $response->assertViewHas('total', 40);
        $response->assertSee($show->title);
    }

    /**
     * Test adding a show to the cart.
     */
    public function test_store_adds_item_to_cart(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create();

        $response = $this->actingAs($user)->post(route('cart.store'), [
            'show_id' => $show->id,
            'quantity' => 1
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 1
        ]);
    }

    /**
     * Test updating a cart item quantity.
     */
    public function test_update_modifies_quantity(): void
    {
        $user = User::factory()->create();
        $cartItem = Cart::factory()->create([
            'user_id' => $user->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user)->put(route('cart.update', $cartItem), [
            'quantity' => 5
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertEquals(5, $cartItem->fresh()->quantity);
    }

    /**
     * Test deleting a cart item.
     */
    public function test_destroy_removes_item(): void
    {
        $user = User::factory()->create();
        $cartItem = Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('cart.destroy', $cartItem));

        $response->assertRedirect(route('cart.index'));
        $this->assertSoftDeleted('carts', ['id' => $cartItem->id]);
    }

    /**
     * Test unauthorized user cannot modify someone else's cart.
     */
    public function test_unauthorized_user_cannot_access_other_cart(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $cartItem = Cart::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete(route('cart.destroy', $cartItem));

        $response->assertStatus(403);
        $this->assertDatabaseHas('carts', ['id' => $cartItem->id, 'deleted_at' => null]);
    }
}
