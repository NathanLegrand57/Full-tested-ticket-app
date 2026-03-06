<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentMicroservice
{
    protected string $baseUrl;
    protected string $jwtSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.payment_service.base_url');
        $this->jwtSecret = config('services.payment_service.jwt_secret');
    }

    /**
     * Create a payment intent in the microservice.
     */
    public function createPayment(string $orderId, float $amount, string $currency = 'eur')
    {
        // Convert amount to cents for Stripe
        $amountInCents = (int) ($amount * 100);

        Log::info("Payment Request to: {$this->baseUrl}/payments", [
            'order_id' => $orderId,
            'amount' => $amountInCents
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->generateToken(),
            ])
                ->timeout(10)
                ->post("{$this->baseUrl}/payments", [
                    'order_id' => $orderId,
                    'amount' => $amountInCents,
                    'currency' => $currency,
                ]);

            if ($response->failed()) {
                Log::error('Payment MS Error: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Payment MS Connection Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate a simple HS256 JWT for service-to-service auth.
     */
    protected function generateToken(): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'iss' => 'laravel-app',
            'iat' => time(),
            'exp' => time() + 60 // 1 minute expiry
        ]);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->jwtSecret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    protected function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
