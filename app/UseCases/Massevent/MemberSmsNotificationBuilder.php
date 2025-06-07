<?php

namespace App\UseCases\Massevent;

use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberSmsNotification;
use App\Models\Massevent\TicketSet;
use App\UseCases\Massevent\SmsTemplates\AboutBookingToMasseventSmsTemplate;
use App\UseCases\Massevent\SmsTemplates\SmsTemplateInterface;

class MemberSmsNotificationBuilder
{


    public static function createSmsNotificationAboutBookingToMassevent(TicketSet $ticketSet): ?MemberSmsNotification
    {

        /**
         * @var Member $member
         */
        $member = Member::find($ticketSet->member_id);
        if (!isset($member)){
            return null;
        }

        /**
         * @var Massevent $massevent
         */
        $massevent = $member->massevent;

        $smsTemplate = new AboutBookingToMasseventSmsTemplate(
            eventName: $massevent->getName()
            , registrationDate: $massevent->getDateInHumanFormat()
            , registrationTimeFrom: $massevent->getTimeInHumanFormatFrom()
            , registrationTimeTo: $massevent->getTimeInHumanFormatTo()
            , mapUrl: $massevent->place->getMapUrl() ?? ''
            , ticketQuantity: $member->ticket_plan
            , qrUrl: $member->ticket_url
        );

        $smsNotification = new MemberSmsNotification([
            'member_id' => $member->id,
            'real_used_phone_at_sending' => $member->phone_for_massevent,
            'text' => $smsTemplate->getContent(),
            'type' => 'booking_to_massevent'
        ]);

        $smsNotification->save();

        return $smsNotification->refresh();
    }
}
