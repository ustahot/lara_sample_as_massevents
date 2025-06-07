<?php

namespace App\UseCases\Massevent;

use App\Jobs\Massevent\Member\LinkManagerJob;
use App\Models\Massevent\Member;

class LaterLinkManageCase
{

    public static function do()
    {
        $members = Member::whereNull('manager_id')
            ->whereNotNull('manager_guid')
            ->limit(1000)
            ->get()
        ;

        foreach ($members as $member) {
            LinkManagerJob::dispatch($member)->onQueue('latter_link_manager');
        }

        return $members;
    }

}
