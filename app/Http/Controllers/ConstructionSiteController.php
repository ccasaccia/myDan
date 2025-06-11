<?php

namespace App\Http\Controllers;

use App\Models\ConstructionSite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConstructionSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $constructionSites = ConstructionSite::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('ConstructionSites/Index', [
            'constructionSites' => $constructionSites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ConstructionSites/View');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:planned,active,completed,on_hold',
            'location' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'budget' => 'nullable|numeric',
            'actual_cost' => 'nullable|numeric',
            'description' => 'nullable|string|max:255',
            'details' => 'required|string|max:255',
            'permit_number' => 'nullable|string|max:255',
            'safety_inspection_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        ConstructionSite::create($validated);

        return redirect()->route('construction-sites.index')->with('success', 'Cantiere creato con successo.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConstructionSite $constructionSite)
    {
        return Inertia::render('ConstructionSites/View', [
            'constructionSite' => $constructionSite
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
