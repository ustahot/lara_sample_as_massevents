<?php

namespace App\UseCases\Massevent;

use App\Http\Requests\Massevent\Api\Member\StoreRequest;
use App\Jobs\Massevent\Member\LinkManagerJob;
use App\Models\HR\Employee;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;
use App\Models\Massevent\MemberNote;
use App\Models\Massevent\MemberNoteType;
use App\Models\Massevent\MemberStatus;
use function PHPUnit\Framework\isFalse;

class MemberBuilder
{
    public static function createModelInstancesFromWeb(array $validated)
    {

        if (isset($validated['guid_1c'])) {
//            $employee = Employee::findByCode($validated['guid_1c']);
            $employee = Employee::firstOrCreate(['code' => $validated['guid_1c']]);
            $validated['employee_id'] = $employee->id;
        }

        if (!isset($validated['category_id'])) {
            $category = MemberCategory::findByCode('web_created');
            $validated['category_id'] = $category->id ?? null;
        }

        $member = Member::create($validated);

        /**
         * @var  Member $member
         */
        $member->setHashId();
        $member->save();

        MemberStatus::changeForTicketPlanWheMemberCreated($member);
        LinkManagerJob::dispatch($member);

        return $member;
    }

    public static function createOrUpdateFromLandingCons(array $validated)
    {

        if (isset($validated['guid_1c'])) {
            $employee = Employee::findByCode($validated['guid_1c']);
            $validated['employee_id'] = $employee->id;
        }

        $category = MemberCategory::findByCode($validated['category_code']);
        $validated['category_id'] = $category->id ?? null;

        $member = Member::findByPhoneForMassevent($validated['phone_for_massevent']);
        if (!isset($member)) {
            $member = Member::create($validated);

            /**
             * @var  Member $member
             */
            $member->setHashId();
            $member->save();

            MemberStatus::changeForTicketPlanWheMemberCreated($member);
            LinkManagerJob::dispatch($member);

            return $member;
        } else {
            self::updateInstanceFromWeb($validated, $member);
        }


        /**
         * @var  Member $member
         */
        $member->setHashId();
        $member->save();

        MemberStatus::changeForTicketPlanWheMemberCreated($member);

        return $member;
    }


    public static function updateInstanceFromWeb(array $validated, Member $member)
    {
        // Проверяем, изменилось ли количество забронированных билетов
        if (isset($validated['ticket_plan']) && $validated['ticket_plan'] !== $member->ticket_plan) {
            $member->update($validated);
            MemberStatus::changeForTicketPlanWhenMemberUpdated($member); // Прогоняем через вероятное переопределение статуса, т.к. изменилось количество забронированных билетов
        } else {
            $member->update($validated);
        }
    }
}
