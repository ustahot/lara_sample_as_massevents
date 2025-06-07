<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Massevent\MemberCategory\MemberCategoryResource;
use App\Models\Massevent\MemberCategory;
use Illuminate\Http\Request;

class MemberCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MemberCategoryResource::collection(MemberCategory::all())->resolve();
    }

    public function indexSort()
    {
        return MemberCategoryResource::collection(MemberCategory::where('id','>', 0)->orderBy('sort')->get())->resolve();
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
    public function show(MemberCategory $memberCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberCategory $memberCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberCategory $memberCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberCategory $memberCategory)
    {
        //
    }
}
