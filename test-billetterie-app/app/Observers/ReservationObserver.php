<?php

namespace App\Observers;

use App\Mail\ReservationConfirmed;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;


class ReservationObserver
{
    /**
     * Keep track of sent emails in the current request flow to avoid duplicates for orders with multiple items.
     */
    protected static array $sentOrderEmails = [];

    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        $this->sendConfirmationMail($reservation);
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        if ($reservation->wasChanged('status')) {
            $this->sendConfirmationMail($reservation);
        }
    }

    /**
     * Logic to send confirmation mail, handling grouping by payment_id.
     */
    protected function sendConfirmationMail(Reservation $reservation): void
    {
        if ($reservation->status !== 'paid' || !$reservation->user || !$reservation->user->email) {
            return;
        }

        $orderId = $reservation->payment_id;

        // If part of an order, we want to send one mail for all items
        if ($orderId) {
            if (isset(self::$sentOrderEmails[$orderId])) {
                return;
            }

            self::$sentOrderEmails[$orderId] = true;

            // Fetch all reservations for this order to group them in the mail
            // Force loading of user and show to avoid lazy loading issues in the Mailable
            $allReservations = Reservation::where('payment_id', $orderId)
                ->with(['show', 'user'])
                ->get();

            if ($allReservations->isEmpty()) {
                return;
            }

            try {
                \Log::info("Sending grouped mail for order: {$orderId}");
                Mail::to($reservation->user->email)
                    ->send(new ReservationConfirmed($allReservations));
            } catch (\Exception $e) {
                \Log::error("Erreur mail (order: {$orderId}) : " . $e->getMessage());
            }
        } else {
            // Individual reservation without payment_id
            try {
                \Log::info("Sending single mail for reservation: {$reservation->id}");
                Mail::to($reservation->user->email)
                    ->send(new ReservationConfirmed($reservation->load(['show', 'user'])));
            } catch (\Exception $e) {
                \Log::error("Erreur mail (reservation: {$reservation->id}) : " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        if ($reservation->status === 'paid' || ($reservation->status === 'pending' && empty($reservation->payment_id))) {
            $show = $reservation->show;
            if ($show) {
                $show->increment('places_disponibles', $reservation->quantity);
                \Log::info("Stock restauré pour le spectacle: {$show->title} (+{$reservation->quantity} places)");
            }
        }
    }
}








