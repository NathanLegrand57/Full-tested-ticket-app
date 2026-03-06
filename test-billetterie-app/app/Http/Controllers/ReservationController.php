<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of user reservations (history).
     */
    public function index()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['show'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reservations.history', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * Display the specified reservation details.
     */
    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $reservation->load('user', 'show');

        return view('reservations.show', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Cancel a reservation.
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $reservation->delete();

        return redirect()->route('reservations.history')->with('success', 'Réservation annulée avec succès');
    }
}
