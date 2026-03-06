<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Show;
use App\Models\Reservation;
use App\Services\ReservationFactory;
use App\Services\PaymentMicroservice;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the cart items.
     */
    public function index()
    {
        \Log::info('Cart index visited');
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('show')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->show->price * $item->quantity;
        });

        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'show_id' => 'required|exists:shows,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $existingCart = Cart::where('user_id', $user->id)
            ->where('show_id', $request->show_id)
            ->first();

        if ($existingCart) {
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'show_id' => $request->show_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Billet ajouté au panier');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Panier mis à jour');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Billet supprimé du panier');
    }

    /**
     * Show checkout/payment page.
     */
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('show')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->show->price * $item->quantity;
        });

        return view('cart.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => $user,
        ]);
    }

    /**
     * Process payment and create reservations.
     */
    public function processPayment(Request $request, PaymentMicroservice $paymentService)
    {
        try {
            \Log::info('Process Payment START');
            $request->validate([
                'payment_method' => 'required|in:card,bank_transfer',
            ]);
            \Log::info('Validation passed');

            $user = Auth::user();
            $cartItems = Cart::where('user_id', $user->id)->with('show')->get();

            if ($cartItems->isEmpty()) {
                \Log::warning('Cart is empty');
                return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
            }

            $total = $cartItems->sum(fn($item) => $item->show->price * $item->quantity);
            \Log::info('Total calculated: ' . $total);

            if ($request->payment_method === 'card') {
                \Log::info('Payment method: card');
                // Initiate Stripe payment via Microservice
                $orderId = 'ORDER-' . uniqid();
                $paymentData = $paymentService->createPayment($orderId, $total);

                if (!$paymentData || !isset($paymentData['client_secret'])) {
                    \Log::error('Payment MS failed to return client_secret');
                    return redirect()->route('cart.checkout')->with('error', 'Erreur lors de l\'initialisation du paiement');
                }

                \Log::info('Client secret received: ' . $paymentData['client_secret']);

                // Save pending reservations
                DB::transaction(function () use ($cartItems, $user, $orderId) {
                    foreach ($cartItems as $item) {
                        Reservation::create([
                            'user_id' => $user->id,
                            'show_id' => $item->show_id,
                            'quantity' => $item->quantity,
                            'amount' => $item->show->price * $item->quantity,
                            'status' => 'pending',
                            'payment_id' => $orderId,
                        ]);
                    }
                });
                \Log::info('Pending reservations saved');

                return redirect()->route('cart.payment-confirm', [
                    'client_secret' => $paymentData['client_secret'],
                    'order_id' => $orderId
                ]);
            }

            \Log::info('Payment method: bank_transfer');
            $factory = new ReservationFactory();

            DB::transaction(function () use ($cartItems, $user, $factory) {
                foreach ($cartItems as $item) {
                    /** @var Show $show */
                    $show = Show::where('id', $item->show_id)->lockForUpdate()->first();

                    if (!$show) {
                        throw new \RuntimeException('Spectacle introuvable.');
                    }

                    if ($show->places_disponibles < $item->quantity) {
                        throw new \RuntimeException('Plus de places disponibles pour ' . $show->title);
                    }

                    // Decrement of available seats stock
                    $show->places_disponibles -= $item->quantity;
                    $show->save();

                    // Creation of the reservation via the factory (Factory pattern)
                    $factory->createFromCartItem($user, $item);
                }

                // Empty the cart only if everything went well
                Cart::where('user_id', $user->id)->delete();
            });

            return redirect()->route('cart.success');
        } catch (\Exception $e) {
            \Log::error('Process Payment CRASH: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart.checkout')->with('error', 'Erreur lors du traitement du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Show the Stripe payment confirmation page.
     */
    public function confirmPayment(Request $request)
    {
        $clientSecret = $request->query('client_secret');
        $orderId = $request->query('order_id');

        if (!$clientSecret || !$orderId) {
            return redirect()->route('cart.checkout')->with('error', 'Session de paiement invalide');
        }

        return view('cart.payment-confirm', [
            'clientSecret' => $clientSecret,
            'orderId' => $orderId,
            'stripeKey' => env('STRIPE_KEY')
        ]);
    }

    /**
     * Handle payment success notification from frontend.
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->input('order_id');
        $user = Auth::user();

        try {
            DB::transaction(function () use ($orderId, $user) {
                $reservations = Reservation::where('payment_id', $orderId)
                    ->where('user_id', $user->id)
                    ->get();

                foreach ($reservations as $reservation) {
                    $show = $reservation->show;

                    if ($show) {
                        $show->decrement('places_disponibles', $reservation->quantity);

                        $reservation->status = 'paid';
                        $reservation->save();
                    }
                }

                Cart::where('user_id', $user->id)->delete();
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error("Payment success error for order: {$orderId} - " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Show the payment success page.
     */
    public function success()
    {
        return view('cart.success');
    }
}
