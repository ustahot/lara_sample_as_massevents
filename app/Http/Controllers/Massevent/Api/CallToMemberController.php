<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\CallToMember\StoreRequest;
use App\Models\Massevent\Member;
use App\UseCases\Massevent\CallToMemberBuilder;

class CallToMemberController extends Controller
{
    public function store(StoreRequest $request, Member $member)
    {
        $validated = $request->validated();
        $call = CallToMemberBuilder::createAndDoCallToMember($validated, $member);
        return $call;
    }
}
