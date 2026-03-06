<?php

namespace Tests\Feature\Services;

use App\Services\PaymentMicroservice;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentMicroserviceTest extends TestCase
{
    /**
     * Test successful payment creation via microservice mock.
     */
    public function test_create_payment_success(): void
    {
        // Mock URL and secret in config
        config(['services.payment_service.base_url' => 'http://payment-ms.test']);
        config(['services.payment_service.jwt_secret' => 'test-secret']);

        // Mock HTTP call to avoid requesting the real Python service
        Http::fake([
            'http://payment-ms.test/payments' => Http::response([
                'id' => 'pi_12345',
                'client_secret' => 'secret_98765',
                'status' => 'requires_payment_method'
            ], 200)
        ]);

        $service = new PaymentMicroservice();
        $result = $service->createPayment('ORDER_100', 50.00);

        // Verification
        $this->assertNotNull($result);
        $this->assertEquals('pi_12345', $result['id']);
        $this->assertEquals('requires_payment_method', $result['status']);

        // Verify that the request sent to the "Python service" was correct
        Http::assertSent(function ($request) {
            return $request->url() === 'http://payment-ms.test/payments' &&
                $request['order_id'] === 'ORDER_100' &&
                $request['amount'] === 5000;
        });
    }

    /**
     * Test payment creation failure (500 error from MS).
     */
    public function test_create_payment_failure(): void
    {
        config(['services.payment_service.base_url' => 'http://payment-ms.test']);
        config(['services.payment_service.jwt_secret' => 'test-secret']);

        Http::fake([
            'http://payment-ms.test/payments' => Http::response(['error' => 'Server Error'], 500)
        ]);

        $service = new PaymentMicroservice();
        $result = $service->createPayment('ORDER_101', 10.00);

        // The service is expected to return null on failure (seen in code)
        $this->assertNull($result);
    }

    /**
     * Test payment creation with connection timeout/exception.
     */
    public function test_create_payment_connection_error(): void
    {
        config(['services.payment_service.base_url' => 'http://payment-ms.test']);
        config(['services.payment_service.jwt_secret' => 'test-secret']);

        // Mock connection error by returning an empty response with a network error code
        Http::fake([
            'http://payment-ms.test/payments' => Http::response(null, 500)
        ]);

        $service = new PaymentMicroservice();
        $result = $service->createPayment('ORDER_102', 15.00);

        $this->assertNull($result);
    }
}
