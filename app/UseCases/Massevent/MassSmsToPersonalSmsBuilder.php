<?php

namespace App\UseCases\Massevent;

use App\Jobs\Massevent\MassSms\GeneratePersonalSmsJob;
use App\Models\Massevent\MassSms;
use App\Models\Massevent\MassSmsByPersonal;
use App\Models\Massevent\Member;


/**
 * @property int[] membersCollection
 */
class MassSmsToPersonalSmsBuilder
{
    private array $memberIdCollection;

    public function __construct(private readonly MassSms $massSms)
    {
        $this->memberIdCollection = json_decode($this->massSms->member_id_collection, true);
    }

    public function generatePersonalSmsJobs()
    {

        foreach ($this->memberIdCollection as $memberId) {

            /**
             * @var Member $member
             */
            $member = Member::find($memberId);
            if (!isset($member)){
                continue;
            }

            $renderedText = self::replacePlaceholder($this->massSms->text, $member);

            $personalSms = MassSmsByPersonal::create([
                'parent_mass_sms_id' => $this->massSms->id,
                'real_used_phone_at_sending' => $member->phone_for_massevent,
                'text' => $renderedText,
            ]);

            GeneratePersonalSmsJob::dispatch($personalSms)->onQueue('personal_sms_' . $this->massSms->id);
        }

    }

    private static function replacePlaceholder(string $draftContent, Member $member)
    {
//        $placeholders = MassSmsPlaceholder::all();

        $renderedText = $draftContent;
        $renderedText = str_replace('{ticket_url}', $member->ticket_url, $renderedText);
        $renderedText = str_replace('{ticket_plan}', $member->ticket_plan, $renderedText);
        $renderedText = str_replace('{ticket_word}', self::makeTicketWord($member->ticket_plan), $renderedText);

        return $renderedText;
    }

    private static function makeTicketWord(int $ticketQuantity)
    {
        // Подбираем окончание для слова "Билеты"
        if ($ticketQuantity > 20) {
            $lastDigits = $ticketQuantity - ( (int) $ticketQuantity / 10 );
        } else {
            $lastDigits = $ticketQuantity;
        }

        $ticketWord = 'билетов';

        if ($lastDigits == 1) {
            $ticketWord = 'билет';
        } elseif ($lastDigits > 1 && $lastDigits < 5) {
            $ticketWord = 'билета';
        }
//        elseif ($lastDigits >= 5) {
//            $ticketWord = 'билетов';
//        }

        return $ticketWord;
    }

}
