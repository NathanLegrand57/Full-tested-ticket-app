<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Reservation;
use App\Models\Show;
use App\Models\User;

/**
 * Example of the Factory pattern to centralize reservation creation.
 */
class ReservationFactory
{
    /**
     * Create a reservation from a cart item.
     */
    public function createFromCartItem(User $user, Cart $cartItem): Reservation
    {
        /** @var Show $show */
        $show = $cartItem->show;

        return Reservation::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
            'quantity' => $cartItem->quantity,
            'amount' => $show->price * $cartItem->quantity,
        ]);
    }
}








