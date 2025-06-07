<?php

namespace App\UseCases\Massevent;

use App\Exceptions\Massevent\MassSmsException;
use App\Http\Requests\Massevent\Api\Member\FilterRequest;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\MassSms;
use App\Models\Massevent\Member;
use Illuminate\Http\Response;

class MassSmsBuilder
{
    public function __construct(private readonly array $validated, Massevent $massevent)
    {
    }

    public function createInstance()
    {
        $filter = MemberFilter::make($this->validated);

        /**
         * @var Member[] $members
         */
        $members = $filter->getFilteredCollection()->get();

        if ($members->count() !== (int) $this->validated['member_quantity']) {
            throw new MassSmsException('Плановое количество участников, в отфильтрованном наборе, не совпадает с фактическим. '
                . $this->validated['member_quantity'] . ' и ' . $members->count() . ' соответственно'
                , Response::HTTP_CONFLICT);
        }

        $phones = [];
        $memberIdCollection = [];
        foreach ($members as $member) {
            $phones[] = $member->phone_for_massevent;
            $memberIdCollection[] = $member->id;
        }

        $massSms = new MassSms([
            'massevent_id' => $this->validated['massevent_id'],
            'real_used_phones_at_sending' => json_encode($phones),
            'member_id_collection' => json_encode($memberIdCollection),
            'text' => $this->validated['text'],
//            'text' => json_encode($this->validated['text'], JSON_UNESCAPED_UNICODE),
        ]);

        $massSms->save();

        return $massSms->refresh();
    }
}
