<?php

namespace App\UseCases\Massevent;

use App\Models\HR\Employee;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberNote;
use App\Models\Massevent\MemberNoteType;

class MemberNoteBuilder
{
    public static function createModelInstances(array $validated, Member $member)
    {
        $validated['member_id'] = $member->id;

        if (isset($validated['guid_1c'])) {
            $employee = Employee::findByCode($validated['guid_1c']);
            if (!isset($employee)) {
                $employee = Employee::create(['code' => $validated['guid_1c']]);
                $employee->refresh();
            }
            $validated['employee_id'] = $employee->id;
        }

        $noteType = MemberNoteType::getUserType(); // todo реализовать определение типа примечания
        $validated['type_id'] = $noteType->id ?? null;
        return MemberNote::create($validated);
    }
}
