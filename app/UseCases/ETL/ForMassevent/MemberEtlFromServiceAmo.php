<?php

namespace App\UseCases\ETL\ForMassevent;

use App\Models\ETL\ImportSession;
use App\Models\ETL\StageExtractMember;
use App\Models\ETL\StageTransformMember;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;
use App\Services\AMO\Inetgrtions\FromWf\ContactService;
use App\UseCases\ETL\EtlFromFileInterface;
use App\UseCases\ETL\EtlFromServiceInterface;

class MemberEtlFromServiceAmo implements \App\UseCases\ETL\EtlFromServiceInterface
{

    private function __construct(
        private readonly ImportSession $session
        , private readonly Massevent $massevent
        , private readonly ?array $attributes
    ){}

    public static function make(
        ImportSession $session,
        Massevent $massevent,
        ?array $attributes = null
    ): EtlFromServiceInterface
    {
        return new self(session: $session, massevent: $massevent, attributes: $attributes);
    }

    public function extract(): int
    {

        $service = new ContactService();
        $collection = $service->getContactsForDod();

        $totalCount = 0;
        if (isset($collection['sale'])) {
            $category = MemberCategory::findByCode('sale_pipeline');
            if (isset($category)){ // ранний выход тут не применим
                $totalCount += $this->importPipeline(amoData: $collection['sale'], memberCategory: $category);
            }
        }

        if (isset($collection['talk'])) {
            $category = MemberCategory::findByCode('talk_pipeline');
            if (isset($category)){ // ранний выход тут не применим
                $totalCount += $this->importPipeline(amoData: $collection['talk'], memberCategory: $category);
            }
        }

        return $totalCount;
    }

    public function transform(): int
    {
        /**
         * @var StageExtractMember[] $collection
         */
        $collection = StageExtractMember::where('session_id', $this->session->id)->get();

        $count = 0;
        foreach ($collection as $item) {

            $digitPhone = preg_replace("/[^0-9]/", '', $item->phone);
            if (strlen($digitPhone) === 11 && str_starts_with($digitPhone, '8')) {
                $clearPhone = '7' . substr($digitPhone, 2);
            } else {
                $clearPhone = $digitPhone;
            }

            StageTransformMember::create([
                'session_id' => $this->session->id,
                'massevent_id' => $item->massevent_id,
                'stage_extract_id' => $item->id,
                'category_code' => $item->category_code,
                'outer_id' => $item->outer_id,
                'name' => $item->name,
//                'name' => preg_replace("/(\[ВИП\]|\[ВИП\] )/", "", $item->name),
                'phone' => $clearPhone,
//                'manager_name' => $item->manager_name,
//                'member_guid' => $item->member_guid,
//                'manager_guid' => $item->manager_guid,
                'status' => 'transformed',
            ]);
            $count ++;
        }

        return $count;

    }

    public function load(): int
    {
        /**
         * @var StageTransformMember[] $collection
         */

        $collection = StageTransformMember::where('session_id', $this->session->id)->get();

        $count = 0;
        foreach ($collection as $item) {
            $member = Member::where('massevent_id', $this->massevent->id)->where('phone_for_massevent', $item->phone)->first();
            if (isset($member)) {
                $item->update(['status' => 'already_exist']);
                continue;
            }

            /**
             * @var MemberCategory $category
             */
            $category = MemberCategory::findByCode($item->category_code);

            Member::create([
                'massevent_id' => $item->massevent_id,
                'name_for_massevent' => $item->name,
                'phone_for_massevent' => $item->phone,
                'category_id' => $category->id ?? null,
                'manager_name' => $item->manager_name,
                'member_guid' => $item->member_guid,
                'manager_guid' => $item->manager_guid,
            ]);
            $item->update(['status' => 'loaded']);

            $count ++;
        }

        return $count;

    }

    public static function validated(?array $attributes = null): ?array
    {
        // TODO: Implement validated() method.
    }

    private function importPipeline(array $amoData, MemberCategory $memberCategory): int
    {

        $count = 0;
        foreach ($amoData as $datum) {

            if (!isset($datum['custom_fields_values'])){
                continue;
            }

            foreach ($datum['custom_fields_values'] as $customField) {
                if (!isset($customField['field_code']) || strtoupper($customField['field_code']) !== 'PHONE'
                    || !isset($customField['values'])) {
                    continue;
                }

                $draftPhone = $customField['values'][0]['value'];
            }

            StageExtractMember::create([
                'session_id' => $this->session->id,
                'massevent_id' => $this->massevent->id,
                'category_code' => $memberCategory->code,
                'outer_id' => $datum['id'],
                'name' => $datum['name'],
                'phone' => $draftPhone,
                'manager_amo_id' => $datum['responsible_user_id'],
            ]);
            $count ++;
        }

        return $count;
    }
}
