<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\MemberSmsSimpleMessage\StoreRequest;
use App\Http\Resources\Massevent\MemberSmsSimpeMessage\MemberSmsSimpleMessageResource;
use App\Models\Massevent\Member;
use App\UseCases\Massevent\MemberSmsSimpleMessageBuilder;

class MemberSmsSimpleMessageController extends Controller
{
    public function store(StoreRequest $request, Member $member)
    {
        $validated = $request->validated();
        $smsMessage = MemberSmsSimpleMessageBuilder::createAndSendSmsToMember($validated, $member);
        return MemberSmsSimpleMessageResource::make($smsMessage)->resolve();
    }
}
