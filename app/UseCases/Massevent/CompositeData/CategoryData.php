<?php

namespace App\UseCases\Massevent\CompositeData;

use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;

class CategoryData
{
    public static function getNoEmptyCategoriesFromMassevent(Massevent $massevent)
    {
        return MemberCategory::whereIn('id', Member::where('massevent_id', $massevent->id)->get('category_id'))
            ->orderBy('sort')
            ->get();
    }
}
