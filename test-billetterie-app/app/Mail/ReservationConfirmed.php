<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reservations;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param \Illuminate\Support\Collection|\App\Models\Reservation $reservations
     */
    public function __construct($reservations)
    {
        if ($reservations instanceof Reservation) {
            $this->reservations = collect([$reservations]);
            $this->user = $reservations->user;
        } else {
            $this->reservations = $reservations;
            $this->user = $reservations->first()->user;
        }
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('Confirmation de votre commande - ' . config('app.name'))
            ->view('mail.reservation-confirmed');
    }
}








