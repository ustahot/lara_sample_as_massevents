<?php

namespace App\UseCases\ETL\ForMassevent;

use App\Models\ETL\ImportSession;
use App\Models\ETL\StageExtractMember;
use App\Models\ETL\StageTransformMember;
use App\Models\Massevent\Massevent;
use App\Models\Massevent\Member;
use App\Models\Massevent\MemberCategory;
use App\UseCases\ETL\ETLInterface;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class WasIsmailovoMemberETL implements ETLInterface
{

    public function __construct(
        private ImportSession $session
        , private Massevent $massevent
        , private string $fileName
        , private string $categoryCode
    )
    {
    }

    public function extract(): int
    {


        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        $spreadSheet = $reader->load($this->fileName);
        $currentSheet = $spreadSheet->getSheet(0);
        $collection = $currentSheet->getCellCollection();
        $bottomRight = $collection->getHighestRowAndColumn(); // Право-низ таблицы

        for($row = 1; $row <= $bottomRight['row']; $row++) {
            $draftName = $currentSheet->getCell('B' . $row);

            $draftPhone = $currentSheet->getCell('C' . $row);
            StageExtractMember::create([
                'session_id' => $this->session->id,
                'massevent_id' => $this->massevent->id,
                'category_code' => $this->categoryCode,
                'outer_id' => $currentSheet->getCell('A' . $row),
                'name' => $draftName,
                'phone' => $draftPhone,
                'manager_name' => $currentSheet->getCell('D' . $row),
                'member_guid' => $currentSheet->getCell('E' . $row),
                'manager_guid' => $currentSheet->getCell('F' . $row),
            ]);
        }

        return $row;
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
}
