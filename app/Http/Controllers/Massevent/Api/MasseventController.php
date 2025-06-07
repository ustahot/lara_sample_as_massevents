<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\Massevent\StoreRequest;
use App\Http\Requests\Massevent\Api\Massevent\UpdateRequest;
use App\Http\Resources\Massevent\Massevent\MasseventCategoryResource;
use App\Http\Resources\Massevent\Massevent\MasseventResource;
use App\Http\Resources\Massevent\Massevent\MasseventWithAggregateResource;
use App\Models\Massevent\Massevent;
use App\UseCases\Massevent\CompositeData\CategoryData;
use App\UseCases\Massevent\MasseventBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;


class MasseventController extends Controller
{
    use SoftDeletes;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Massevent::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Massevent $massevent)
    {
        return MasseventWithAggregateResource::make($massevent);
//        return MasseventResource::make($massevent);
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
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $massevent = MasseventBuilder::createModelInstances($validated);
        return MasseventResource::make($massevent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Massevent $massevent)
    {
        $validated = $request->validated();
        MasseventBuilder::updateModelInstances($validated, $massevent);
        return MasseventResource::make($massevent);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Massevent $massevent)
    {
        //
    }


    public function notEmptyCategories(Massevent $massevent)
    {
        return MasseventCategoryResource::collection(CategoryData::getNoEmptyCategoriesFromMassevent(massevent: $massevent))->resolve();
    }
}
