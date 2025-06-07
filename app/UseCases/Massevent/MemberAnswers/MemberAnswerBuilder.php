<?php

namespace App\UseCases\Massevent\MemberAnswers;

use App\Models\Massevent\Massevent;
use App\Models\Massevent\MassSms;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberAnswer;
use App\UseCases\Massevent\MemberFilter;

class MemberAnswerBuilder
{
    public function __construct(private readonly array $validated)
    {
    }

    public function createInstance()
    {

        $attributes = $this->validated;
        $attributes['status'] = 'new';
        $answer = new MemberAnswer($attributes);
        if ($answer->type === 'participation_in_massevent') {
            $case = new ParticipationInMasseventAnswerCase($answer);
            $case->do();
        }


        return $answer->refresh();
    }

}
