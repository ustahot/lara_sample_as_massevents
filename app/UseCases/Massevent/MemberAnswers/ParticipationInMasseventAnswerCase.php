<?php

namespace App\UseCases\Massevent\MemberAnswers;

use App\Models\Massevent\Member;
use App\Models\Massevent\MemberAnswer;
use App\Models\Massevent\MemberCategory;
use App\Models\Massevent\MemberStatus;
use App\UseCases\Massevent\Statuses\MemberStatusRefused;

class ParticipationInMasseventAnswerCase implements AnswerCaseInterface
{

    public function __construct(private MemberAnswer $memberAnswer)
    {
    }

    public function do()
    {
        if ($this->memberAnswer->answer === 'refuse') {
            MemberStatus::set($this->memberAnswer->member, MemberStatusRefused::getCode());
        } elseif ($this->memberAnswer->answer === 'telegram') {
            $this->memberAnswer->member->category_id = MemberCategory::findByCode('needle_telegram_message')->id;
            $this->memberAnswer->member->status = null;
            $this->memberAnswer->member->save();
        } elseif ($this->memberAnswer->answer === 'call') {
            $this->memberAnswer->member->category_id = MemberCategory::findByCode('needle_phone_call')->id;
            $this->memberAnswer->member->status = null;
            $this->memberAnswer->member->save();
        }

        $this->memberAnswer->status = 'handled';
        return $this->memberAnswer->save();
    }
}
