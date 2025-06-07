<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Models\Massevent\MasseventPlace;
use Illuminate\Http\Request;

class MasseventPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MasseventPlace::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MasseventPlace $masseventPlace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasseventPlace $masseventPlace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasseventPlace $masseventPlace)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasseventPlace $masseventPlace)
    {
        //
    }
}
