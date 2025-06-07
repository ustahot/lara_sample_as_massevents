<?php

namespace App\Jobs\HR;

use App\Models\HR\AnswerToApplicant;
use App\Services\Email\Default\EmailService;
use App\UseCases\HR\SendAnswerToHackathonApplicantCase;
use App\UseCases\Massevent\MemberAnswers\ParticipationInMasseventAnswerCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAnswerToApplicantJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly AnswerToApplicant $answerToApplicant)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $case = new SendAnswerToHackathonApplicantCase(answerToApplicant: $this->answerToApplicant);
        $service = new EmailService();
        $case->do($service);
    }
}
