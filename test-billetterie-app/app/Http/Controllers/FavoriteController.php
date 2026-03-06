<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite for a show (add or remove).
     */
    public function store(Show $show)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $favorite = UserFavorite::where('user_id', $user->id)
                                 ->where('show_id', $show->id)
                                 ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $isFavorited = false;
            $message = 'Spectacle retiré des favoris';
        } else {
            // Add to favorites
            UserFavorite::create([
                'user_id' => $user->id,
                'show_id' => $show->id,
            ]);
            $isFavorited = true;
            $message = 'Spectacle ajouté aux favoris';
        }

        // Return JSON response for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'isFavorited' => $isFavorited,
                'favoritesCount' => $show->favorites_count,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Display the user's favorite shows.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $favoriteShows = $user->favoriteShows()
                               ->withCount('favorites')
                               ->paginate(10);

        return view('favorites.index', compact('favoriteShows'));
    }
}
