<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Massevent\Member\MemberForReportWithoutIsmailovoResource;
use App\Models\Massevent\Massevent;
use App\UseCases\Massevent\Reports\MembersWithoutIsmailovo\MembersWithoutIsmailovoCatReportBuilder;
use App\UseCases\Massevent\Reports\Stat\MasseventStatReportBuilder;
use App\UseCases\Massevent\Reports\StatWithCalls\MasseventStatWithCallsReportBuilder;
use App\UseCases\Massevent\Reports\StatWithCallsByShortPhone\MasseventStatWithCallsByShortPhoneReportBuilder;
use App\UseCases\Massevent\Reports\StatWithoutCalls\MasseventStatWithoutCallsReportBuilder;


class ReportController extends Controller
{
    public function stat(Massevent $massevent)
    {
        $result = new MasseventStatReportBuilder($massevent);
        return response($result->getData(), 200);
    }

    public function statWithCalls(Massevent $massevent)
    {
        $result = new MasseventStatWithCallsReportBuilder($massevent);
        return response($result->getData(), 200);
    }

    public function statWithoutCalls(Massevent $massevent)
    {
        $result = new MasseventStatWithoutCallsReportBuilder($massevent);
        return response($result->getData(), 200);
    }

    public function statWithDetailsShortPhone(Massevent $massevent)
    {
        $result = new MasseventStatWithCallsByShortPhoneReportBuilder($massevent);
        return response($result->getData(), 200);
    }

    public function membersWithoutIsmailovo(Massevent $massevent)
    {
        $report = new MembersWithoutIsmailovoCatReportBuilder($massevent);
        $result = $report->getData();
        return MemberForReportWithoutIsmailovoResource::collection($result)->resolve();
    }
}
