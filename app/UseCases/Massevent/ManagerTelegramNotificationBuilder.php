<?php

namespace App\UseCases\Massevent;

use App\Models\HR\Employee;
use App\Models\Massevent\ManagerTelegramNotification;
use App\Models\Massevent\Member;
use App\Services\Telegram\TelegramServiceInterface;
use App\UseCases\Massevent\TelegramMessageTaemplates\AboutMemberCameTemplate;

class ManagerTelegramNotificationBuilder
{

    public function __construct(private readonly TelegramServiceInterface $service)
    {
    }

    public function createAndSendNotification(Employee $manager, string $content): ?ManagerTelegramNotification
    {

        if (!isset($manager->telegram_user_id)) {
            return null;
        }

        /**
         * @var ManagerTelegramNotification $notification
         */
        $notification = ManagerTelegramNotification::create([
            'employee_id' => $manager->id
            , 'telegram_user_id' => $manager->telegram_user_id
            , 'content' => $content
        ]);

        $notification->service_response =
            json_encode($notification->send(service: $this->service), JSON_UNESCAPED_UNICODE);

        $notification->setStatusAsSent();
        $notification->save();

        return $notification->refresh();
    }


    public function createAndSendNotificationToManagerAboutMemberCame(Employee $manager, Member $member)
    {
        $template = new AboutMemberCameTemplate(
            memberName: $member->name_for_massevent
            ,memberPhone: $member->phone_for_massevent
            ,ticketQuantity: $member->ticket_fact
        );

        return $this->createAndSendNotification(manager: $manager, content: $template->getContent());
    }

}
