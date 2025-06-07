<?php

namespace App\Http\Controllers\ETL;

use App\Http\Controllers\Controller;
use App\Models\ETL\ImportSession;
use App\Models\Massevent\Massevent;
use App\UseCases\ETL\ForMassevent\MemberEtlFromFileAmo;
use App\UseCases\ETL\ForMassevent\MemberEtlFromFileXlsxCustomDG2;
use App\UseCases\ETL\ForMassevent\MemberEtlFromServiceAmo;

class StageExtractMemberController extends Controller
{
    public function importFromAmoForDod(Massevent $massevent)
    {
        if ($massevent->id !== 4) {
            return 'Нельзя импортировать не в ДОД-мероприятие';
        }

        $inputSource = [
            'etl_class' => MemberEtlFromServiceAmo::class
        ];

        $session = ImportSession::find(53);
//        $session = ImportSession::create([
//            'entity_code' => 'member',
//        ]);

        $etl = $inputSource['etl_class']::make(
            session: $session
            , massevent: $massevent
        );
//        $result = $etl->extract();
//        $result = $etl->transform();
        $result = $etl->load();
        return $result;

    }
}
