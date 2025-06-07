<?php

namespace App\Http\Controllers;

use App\Jobs\HR\FillEmployeeFromCorpFamilyJob;
use App\Jobs\Massevent\Member\LinkManagerJob;
use App\Models\HR\Employee;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Services\Telegram\Integrations\corpfamilybot\CorpFamilyBotService;
use App\UseCases\Massevent\CompositeData\CategoryData;
use App\UseCases\Massevent\ManagerTelegramNotificationBuilder;

class TestController extends Controller
{
    public function test()
    {
        $massevent = Massevent::find(1);
        return CategoryData::getNoEmptyCategoriesFromMassevent(massevent: $massevent);
    }
}
