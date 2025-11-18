<?php

namespace App\Models\Massevent;

interface OuterMemberForImportInterface
{
    public function getMembersPartFromOuterFile(string $fileName);
}
