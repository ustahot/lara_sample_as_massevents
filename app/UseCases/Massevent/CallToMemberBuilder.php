<?php

namespace App\UseCases\Massevent;

use App\Models\Massevent\CallToMember;
use App\Models\Massevent\Member;
use App\Services\Call\Integrations\CallGwd\CallGwdService;

class CallToMemberBuilder
{
    public static function createAndDoCallToMember(array $validated, Member $member): CallToMember
    {

        $call = new CallToMember([
            'member_id' => $member->id,
            'phone_from' => $validated['phone_from'],
            'real_used_phone_at_calling' => $member->phone_for_massevent,
        ]);

        $call->save();
        $callService = new CallGwdService();
        $call->save();
        $response = $call->do(service: $callService);
        $call->data_from_service = $response;
        $call->setCompletedStatus();
        $call->save();

        return $call->refresh();
    }

}
