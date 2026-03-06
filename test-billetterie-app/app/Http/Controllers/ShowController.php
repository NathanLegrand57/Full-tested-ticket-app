<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Services\ShowImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Show::withCount('favorites');

        // Search in title and description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $shows = $query->latest()->paginate(10)->withQueryString();

        return view('shows.index', compact('shows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->can('show-create')) {
            return view('shows.create');
        }

        abort(401, 'Access denied');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'show_date' => 'required|date|after:now',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'places_disponibles' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imageManager = ShowImageManager::getInstance();
            $validated['image'] = $imageManager->storeShowImage($request->file('image'));
        }

        // Default value if not provided
        if (! isset($validated['places_disponibles'])) {
            $validated['places_disponibles'] = 100;
        }

        $show = Show::create($validated);

        return redirect()->route('shows.index')
            ->with('success', 'Spectacle créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Show $show)
    {
        return view('shows.show', compact('show'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Show $show)
    {
        if (Auth::user()->can('show-edit')) {
            return view('shows.edit', compact('show'));
        }

        abort(401, 'Access denied');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Show $show)
    {
        if (Auth::user()->can('show-edit')) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'show_date' => 'required|date|after:now',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'duration' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'places_disponibles' => 'nullable|integer|min:0',
            ]);

            if ($request->hasFile('image')) {
                $imageManager = ShowImageManager::getInstance();
                $validated['image'] = $imageManager->storeShowImage($request->file('image'), $show->image);
            }

            $show->update($validated);

            return redirect()->route('shows.index')
                ->with('success', 'Spectacle mis à jour avec succès.');
        }
        abort(401, 'Access denied');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Show $show)
    {
        if (Auth::user()->can('show-delete')) {

            if ($show->image) {
                Storage::disk('public')->delete($show->image);
            }

            $show->delete();

            return redirect()->route('shows.index')
                ->with('success', 'Spectacle supprimé avec succès.');
        }
        abort(401, 'Access denied');
    }
}
