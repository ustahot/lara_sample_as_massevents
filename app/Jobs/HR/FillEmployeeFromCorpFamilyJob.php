<?php

namespace App\Jobs\HR;

use App\Models\HR\Employee;
use App\Services\Telegram\Integrations\corpfamilybot\CorpFamilyBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FillEmployeeFromCorpFamilyJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Employee $employee)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new CorpFamilyBotService();
        $serviceResponse = json_decode($service->findUserByGuid1c(guid1c: $this->employee->code), true);

        if ($serviceResponse['result'] !== 'ok' || !isset($serviceResponse['response'][0]['telegram_id'])) {
            $this->employee->setCorpFamilyStatusAssError();
            $this->employee->save();
            return;
        }

        $this->employee->telegram_user_id = $serviceResponse['response'][0]['telegram_id'];
        $this->employee->first_name = trim($serviceResponse['response'][0]['first_name']);
        $this->employee->second_name = trim($serviceResponse['response'][0]['second_name']);
        $this->employee->last_name = trim($serviceResponse['response'][0]['last_name']);
        $this->employee->full_name = $this->employee->last_name . ' '
            . $this->employee->first_name . ' ' . $this->employee->second_name;
        $this->employee->setCorpFamilyStatusAssFilled();
        $this->employee->save();
    }
}
