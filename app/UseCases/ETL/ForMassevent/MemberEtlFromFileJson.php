<?php

namespace App\UseCases\ETL\ForMassevent;

use App\Exceptions\Massevent\ETLException;
use App\Models\ETL\ImportSession;
use App\Models\ETL\StageExtractMember;
use App\Models\ETL\StageTransformMember;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;
use App\UseCases\ETL\EtlFromFileInterface;

class MemberEtlFromFileJson extends ETLAbstract implements EtlFromFileInterface
{

    public function extract(): int
    {

        $data = json_decode(file_get_contents($this->fileName), true);
        $count = 0;
        foreach ($data as $datum) {
            StageExtractMember::create([
                'session_id' => $this->session->id,
                'massevent_id' => $this->massevent->id,
                'category_code' => $this->attributes['category_code'],
//                'outer_id' => $currentSheet->getCell('A' . $row),
                'name' => $datum['USER_NAME'],
                'phone' => $datum['USER_PHONE'],
//                'manager_name' => $currentSheet->getCell('D' . $row),
//                'member_guid' => $currentSheet->getCell('E' . $row),
                'manager_guid' => $datum['USER_MANUAL_SOTR'],
            ]);
            $count ++;
        }

        return $count;
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
                'name' => preg_replace("/(\[ВИП\]|\[ВИП\] )/", "", $item->name),
                'phone' => $clearPhone,
                'manager_name' => $item->manager_name,
                'member_guid' => $item->member_guid,
                'manager_guid' => $item->manager_guid,
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
                'etl_import_session_id' => $this->session->id,
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

    public static function make(
        ImportSession $session, Massevent $massevent, string $fileName, ?array $attributes = null): EtlFromFileInterface {
        self::validated($attributes);
        return new self(
            session: $session,
            massevent: $massevent,
            fileName: $fileName,
            attributes: $attributes
        );
    }

    public static function validated(?array $attributes = null): ?array
    {
        if (!isset($attributes['category_code'])) {
            throw new ETLException('Отсутствует обязательный параметр category_code для процедуры ' . self::class);
        }

        return $attributes;
    }
}
