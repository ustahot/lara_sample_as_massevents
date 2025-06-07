<?php

namespace App\UseCases\Massevent\Reports\MembersWithoutIsmailovo;

use App\Models\Massevent\Massevent;
use App\Models\Massevent\MemberCategory;

class MembersWithoutIsmailovoCatReportBuilder
{
    public function __construct(private readonly Massevent $massevent)
    {
    }

    public function getData()
    {
        $withoutCat = MemberCategory::findByCode('was_in_the_izmailovo');

        return $this->massevent->members->where('category_id', '<>', $withoutCat->id);

    }
}
