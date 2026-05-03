<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Reservation;
use App\Models\Show;
use App\Models\User;
use App\Services\PaymentMicroservice;
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

        $response->assertOk();
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

        $response->assertForbidden();
        $this->assertDatabaseHas('carts', ['id' => $cartItem->id, 'deleted_at' => null]);
    }

    /**
     * Test processing a card payment creates pending reservations and redirects to the confirmation page.
     */
    public function test_process_payment_card_creates_pending_reservation(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create([
            'price' => 25,
            'places_disponibles' => 10,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 2,
        ]);

        $this->mock(PaymentMicroservice::class, function ($mock) {
            $mock->shouldReceive('createPayment')
                ->once()
                ->andReturn([
                    'client_secret' => 'secret_123',
                ]);
        });

        $response = $this->actingAs($user)->post(route('cart.process-payment'), [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('/payment/confirm', $response->headers->get('Location'));
        $this->assertStringContainsString('client_secret=secret_123', $response->headers->get('Location'));

        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 2,
            'amount' => 50,
            'status' => 'pending',
        ]);
    }

    /**
     * Test processing a card payment fails when the payment microservice does not return a client secret.
     */
    public function test_process_payment_card_fails_when_microservice_returns_no_client_secret(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create([
            'price' => 25,
            'places_disponibles' => 10,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 2,
        ]);

        $this->mock(PaymentMicroservice::class, function ($mock) {
            $mock->shouldReceive('createPayment')
                ->once()
                ->andReturn(null);
        });

        $response = $this->actingAs($user)->post(route('cart.process-payment'), [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('cart.checkout'));
        $response->assertSessionHas('error', 'Erreur lors de l\'initialisation du paiement');
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * Test processing payment fails when the cart is empty.
     */
    public function test_process_payment_fails_when_cart_is_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('cart.process-payment'), [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Votre panier est vide');
    }

    /**
     * Test processing a bank transfer payment updates stock, creates a reservation, and empties the cart.
     */
    public function test_process_payment_bank_transfer_updates_stock_and_empties_cart(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create([
            'price' => 30,
            'places_disponibles' => 8,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 3,
        ]);

        $response = $this->actingAs($user)->post(route('cart.process-payment'), [
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertRedirect(route('cart.success'));
        $this->assertEquals(5, $show->fresh()->places_disponibles);
        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 3,
            'amount' => 90,
        ]);
        $this->assertSoftDeleted('carts', [
            'user_id' => $user->id,
            'show_id' => $show->id,
        ]);
    }

    /**
     * Test processing a bank transfer payment fails when available seats are insufficient.
     */
    public function test_process_payment_bank_transfer_fails_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create([
            'price' => 30,
            'places_disponibles' => 1,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 3,
        ]);

        $response = $this->actingAs($user)->post(route('cart.process-payment'), [
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertRedirect(route('cart.checkout'));
        $response->assertSessionHas('error', 'Erreur lors du traitement du paiement: Plus de places disponibles pour ' . $show->title);
        $this->assertEquals(1, $show->fresh()->places_disponibles);
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * Test payment success marks reservations as paid and clears the cart.
     */
    public function test_payment_success_marks_reservation_paid_and_clears_cart(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create([
            'price' => 40,
            'places_disponibles' => 12,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 1,
        ]);

        Reservation::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => 2,
            'amount' => 80,
            'status' => 'pending',
            'payment_id' => 'ORDER-123',
        ]);

        $response = $this->actingAs($user)->postJson(route('cart.payment-success'), [
            'order_id' => 'ORDER-123',
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertEquals(10, $show->fresh()->places_disponibles);
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'show_id' => $show->id,
            'payment_id' => 'ORDER-123',
            'status' => 'paid',
        ]);
        $this->assertSoftDeleted('carts', [
            'user_id' => $user->id,
            'show_id' => $show->id,
        ]);
    }
}
