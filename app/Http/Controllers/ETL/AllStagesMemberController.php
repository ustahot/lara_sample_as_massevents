<?php

namespace App\Http\Controllers\ETL;

use App\Http\Controllers\Controller;
use App\Models\ETL\ImportSession;
use App\Models\Massevent\Massevent;
use App\UseCases\ETL\ForMassevent\MemberEtlFromFileAmo;
use App\UseCases\ETL\ForMassevent\MemberEtlFromFileJson;
use App\UseCases\ETL\ForMassevent\MemberEtlFromFileXlsxCustomDG2;
use App\UseCases\ETL\ForMassevent\MemberEtlFromServiceAmo;

class AllStagesMemberController extends Controller
{
    public function importFromJsonForDod(Massevent $massevent)
    {
        if ($massevent->id !== 4) {
            return 'Нельзя импортировать не в ДОД-мероприятие';
        }

        $inputSource = [
            'category_code' => 'confirmed_not_come_from_pre_event'
            , 'etl_class' => MemberEtlFromFileJson::class
            , 'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/from_old_visit/20251405_1003/pi_events_users_only_data.json',
        ];

//        $session = ImportSession::find(58);
//        $session = ImportSession::create([
//            'entity_code' => 'member',
//        ]);

        $etl = $inputSource['etl_class']::make(
            session: $session
            , fileName: $inputSource['file_name']
            , massevent: $massevent
            , attributes: [
                'category_code' => $inputSource['category_code']
            ]
        );
//        $result = $etl->extract();
//        $result = $etl->transform();
//        $result = $etl->load();
        return $result;

    }
}
