<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display sales statistics for administration.
     */
    public function stats(Request $request)
    {
        $user = Auth::user();

        if (!$user->can('admin-stats') && !$user->can('show-create')) {
            abort(401, 'Access denied');
        }

        $from = $request->input('from');
        $to = $request->input('to');

        $query = Reservation::query();

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $reservations = $query->with('show')->get();

        // Total tickets sold
        $totalTicketsSold = $reservations->sum('quantity');

        // Total revenue
        $totalRevenue = $reservations->sum('amount');

        // Top 3 shows by revenue
        $topShows = $reservations
            ->groupBy('show_id')
            ->map(function ($group) {
                return [
                    'show' => $group->first()->show,
                    'revenue' => $group->sum('amount'),
                    'tickets' => $group->sum('quantity'),
                ];
            })
            ->sortByDesc('revenue')
            ->take(3);

        // Reservations per day (chart-ready)
        $byDay = $query
            ->selectRaw('DATE(created_at) as date, COUNT(*) as reservations_count, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartDates = $byDay->pluck('date')->toArray();
        $chartReservations = $byDay->pluck('reservations_count')->toArray();
        $chartRevenue = $byDay->pluck('revenue')->toArray();

        // Remaining seats per show
        $shows = Show::select('id', 'title', 'places_disponibles')->get();

        return view('admin.stats', [
            'totalTicketsSold' => $totalTicketsSold,
            'totalRevenue' => $totalRevenue,
            'topShows' => $topShows,
            'shows' => $shows,
            'chartDates' => $chartDates,
            'chartReservations' => $chartReservations,
            'chartRevenue' => $chartRevenue,
            'from' => $from,
            'to' => $to,
        ]);
    }
}








