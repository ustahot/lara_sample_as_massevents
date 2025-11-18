<?php

namespace App\Models\Massevent;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Vtiful\Kernel\Excel;

class OuterMemberForImportExcel implements OuterMemberForImportInterface
{
// todo сделать через UseCase
    public function getMembersPartFromOuterFile(string $fileName)
    {

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        $spreadSheet = $reader->load($fileName);
        $stage1 = $spreadSheet->getSheet(0);
        $stage2 = $stage1->getCellCollection();
        $stage2 = $stage1->getCell('D7');
//        $stage2 = $stage1->getHighestRowAndColumn(); // Право-низ таблицы
        dd($stage2);
        return $spreadsheet;
    }
}
