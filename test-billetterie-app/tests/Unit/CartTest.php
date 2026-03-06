<?php

namespace Tests\Unit;

use App\Models\Cart;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * Test cart fillable attributes.
     */
    public function test_cart_fillable_attributes(): void
    {
        $cart = new Cart([
            'user_id' => 1,
            'show_id' => 1,
            'quantity' => 5,
        ]);

        $this->assertEquals(1, $cart->user_id);
        $this->assertEquals(1, $cart->show_id);
        $this->assertEquals(5, $cart->quantity);
    }
}
