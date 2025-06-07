<?php

namespace App\Http\Controllers\Massevent\Api;

use App\Exceptions\Massevent\MemberException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Massevent\Api\Member\FilterRequest;
use App\Http\Requests\Massevent\Api\Member\LandingConsStoreRequest;
use App\Http\Requests\Massevent\Api\Member\SetStatusRequest;
use App\Http\Requests\Massevent\Api\Member\StoreRequest;
use App\Http\Requests\Massevent\Api\Member\UpdateRequest;
use App\Http\Resources\Massevent\Member\MemberResource;
use App\Models\ETL\ImportSession;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberStatus;
use App\Services\Telegram\Integrations\corpfamilybot\CorpFamilyBotService;
use App\UseCases\ETL\ForMassevent\MemberEtlFromFileXlsxCustomDG2;
use App\UseCases\Massevent\LaterLinkManageCase;
use App\UseCases\Massevent\MemberBuilder;
use App\UseCases\Massevent\MemberFilter;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    public function test()
    {
        $service = new CorpFamilyBotService();
//        return $service->sendMessageByTelegramUserId(telegramUserId: '206228169', content: 'test202504101002');
        return $service->findUserByGuid1c(guid1c: 'af1b040f-6499-11e8-8101-005056bc9ca9');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }


    public function indexForMassevent(Request $request, Massevent $massevent)
    {
        return MemberResource::collection($massevent->members)->resolve();
    }

    public function indexForMasseventByFilter(FilterRequest $request, Massevent $massevent)
    {
        $validated = $request->customValidate($massevent);
        $filter = MemberFilter::make($validated);
//        dd($filter->getFilteredCollection()->toSql());
        $filtered = $filter->getFilteredCollection()->orderBy('name_for_massevent')->paginate(30);

        return MemberResource::collection($filtered);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $modelInstance = MemberBuilder::createModelInstancesFromWeb($validated);
        return MemberResource::make($modelInstance)->resolve();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function landingConsStore(LandingConsStoreRequest $request)
    {
        $validated = $request->validated();
        $modelInstance = MemberBuilder::createOrUpdateFromLandingCons($validated);
        return MemberResource::make($modelInstance)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Member $member)
    {
        try {
            $validated = $request->validated();
            MemberBuilder::updateInstanceFromWeb(validated: $validated, member: $member);
            return MemberResource::make($member)->resolve();
        } catch (MemberException $exception) {
            return response([
                'message' => 'Имеются ошибки валидации',
                'errors' => json_decode($exception->getMessage())
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Ручная смена статуса
     *
     * @param SetStatusRequest $request
     * @param Member $member
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function setStatus(SetStatusRequest $request, Member $member)
    {
        $validated = $request->validated();
        $response = MemberStatus::set($member, $validated['status']);
        if (isset($response)) {
            return response($response,\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return MemberResource::make($member)->resolve();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
    }


    public function importFromExcel(Massevent $massevent)
    {
//        die(date('Y-m-d H:i:s ') . 'die');

//        $inputFile = [
//            'category_code' => 'vip',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/import_cons2025_cat_vip.ods',
//            'etl_class' => MemberETL::class
//        ];;
//        $inputFile = [
//            'category_code' => 'close_from_150_to_250',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/import_cons2025_cat_close_150to250.ods',
//            'etl_class' => MemberETL::class
//        ];;
//        $inputFile = [
//            'category_code' => 'close_under_150',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/import_cons2025_cat_close_under150.ods',
//            'etl_class' => MemberETL::class
//        ];;
//        $inputFile = [
//            'category_code' => 'over_250',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/import_cons2025_cat_over250.ods',
//            'etl_class' => MemberETL::class
//        ];;
//        $inputFile = [
//            'category_code' => 'open_under_250',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/import_cons2025_cat_under250.ods',
//            'etl_class' => MemberETL::class
//        ];;
//        $inputFile = [
//            'category_code' => 'was_in_the_izmailovo',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/from_izmailovo/from_izmailovo.xlsx',
//            'etl_class' => WasOnIsmailovoMemberETL::class
//        ];;
//        $inputFile = [
//            'category_code' => 'close_from_150_to_200',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/import_cons2025_cat_close_150to200new.xlsx',
//            'etl_class' => MemberETLXlsx::class
//        ];
//        $inputFile = [
//            'category_code' => 'partner',
//            'file_name' => '/home/g/gudvud/na.gwd.ru/storage/app/private/Massevent/Exchange/Members/20250417/import_cons2025_cat_partner_20250417.xlsx',
//            'etl_class' => MemberETLXlsxCustomDG2::class
//        ];

        /**
         * @var ImportSession $session
         */
//        $session = ImportSession::find(31);
        $session = ImportSession::create([
            'entity_code' => 'member',
        ]);

        $etl = $inputFile['etl_class']::make(
            session: $session
            , massevent: $massevent
            , fileName: $inputFile['file_name']
            , attributes: [
                'category_code' => $inputFile['category_code']
            ]
        );
        $result = $etl->extract();
//        $result = $etl->transform();
//        $result = $etl->load();
        return $result;

    }

    public function laterLinkManager()
    {
        $result = LaterLinkManageCase::do();
        return response($result);
    }
}
