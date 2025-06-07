<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\MemberNote\StoreRequest;
use App\Http\Resources\Massevent\MemberNote\MemberNoteResource;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberNote;
use App\UseCases\Massevent\MemberNoteBuilder;
use Illuminate\Http\Request;

class MemberNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Member $member)
    {
        return MemberNoteResource::collection($member->notes)->resolve();
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
    public function store(StoreRequest $request, Member $member)
    {
        $validated = $request->validated();
        $note = MemberNoteBuilder::createModelInstances($validated, $member);
        return MemberNoteResource::make($note)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberNote $memberNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberNote $memberNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberNote $memberNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberNote $memberNote)
    {
        //
    }
}
