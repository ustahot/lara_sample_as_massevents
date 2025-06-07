<?php

namespace App\Jobs\Massevent\ManagerTelegramNotification;

use App\Models\Massevent\Member;
use App\Services\Telegram\Integrations\corpfamilybot\CorpFamilyBotService;
use App\UseCases\Massevent\ManagerTelegramNotificationBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyAboutMemberCameJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Member $member)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->member->manager_status === 'notified'){
            return;
        }

        $manager = $this->member->manager;
        if (!isset($manager)) {
            $manager = $this->member->employee;
        }
        if (!isset($manager)){
            $this->member->setManagerStatusUndefined();
            $this->member->save();
            return;
        }

        $notifyService = new CorpFamilyBotService();
        $builder = new ManagerTelegramNotificationBuilder(service: $notifyService);
        $notification = $builder->createAndSendNotificationToManagerAboutMemberCame(manager: $manager, member: $this->member);
        $serviceResponse = json_decode($notification->service_response, true);

        if (isset($serviceResponse['response']['result']) && $serviceResponse['response']['result'] === 'ok') {
            $this->member->setManagerStatusNotified();
        } else {
            $this->member->setManagerStatusNotifyFailed();
        }

        $this->member->save();

    }
}
