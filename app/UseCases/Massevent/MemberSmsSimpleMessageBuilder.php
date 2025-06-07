<?php

namespace App\UseCases\Massevent;

use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberSmsNotification;
use App\Models\Massevent\MemberSmsSimpleMessage;
use App\Models\Massevent\TicketSet;
use App\Services\SMS\Integrations\StreamSms\StreamSmsAdapter;
use App\Services\SMS\Integrations\StreamSmsSimple\StreamSmsService;
use App\UseCases\Massevent\SmsTemplates\AboutBookingToMasseventSmsTemplate;
use App\UseCases\Massevent\SmsTemplates\SimpleSmsToMemberTemplate;
use App\UseCases\Massevent\SmsTemplates\SmsTemplateInterface;

class MemberSmsSimpleMessageBuilder
{
    public static function createAndSendSmsToMember(array $validated, Member $member): MemberSmsSimpleMessage
    {

        $smsTemplate = new SimpleSmsToMemberTemplate($validated['text']);

        $smsMessage = new MemberSmsSimpleMessage([
            'member_id' => $member->id,
            'real_used_phone_at_sending' => $member->phone_for_massevent,
            'text' => $smsTemplate->getContent(),
        ]);

        $smsMessage->save();
        $smsService = new StreamSmsService();
        $smsMessage->send(service: $smsService);

        return $smsMessage->refresh();
    }
}
