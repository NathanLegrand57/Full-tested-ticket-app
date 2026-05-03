<?php

namespace Tests\Unit;

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    /**
     * Test that confirmPayment redirects when the payment session is incomplete.
     */
    public function test_confirm_payment_redirects_when_session_is_invalid(): void
    {
        $controller = new CartController();

        $response = $controller->confirmPayment(Request::create('/payment/confirm', 'GET'));

        $this->assertSame(route('cart.checkout'), $response->getTargetUrl());
        $this->assertSame('Session de paiement invalide', $response->getSession()->get('error'));
    }

    /**
     * Test that confirmPayment returns the payment confirmation view when parameters are present.
     */
    public function test_confirm_payment_returns_confirmation_view(): void
    {
        $controller = new CartController();

        $response = $controller->confirmPayment(Request::create('/payment/confirm', 'GET', [
            'client_secret' => 'secret_abc',
            'order_id' => 'ORDER-999',
        ]));

        $this->assertSame('cart.payment-confirm', $response->getName());
        $this->assertSame('secret_abc', $response->getData()['clientSecret']);
        $this->assertSame('ORDER-999', $response->getData()['orderId']);
        $this->assertArrayHasKey('stripeKey', $response->getData());
    }

    /**
     * Test that the success page returns the expected view.
     */
    public function test_success_returns_success_view(): void
    {
        $controller = new CartController();

        $response = $controller->success();

        $this->assertSame('cart.success', $response->getName());
    }
}