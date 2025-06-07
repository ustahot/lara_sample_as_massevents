<?php

namespace App\UseCases\ETL\ForMassevent;

use App\Models\ETL\ImportSession;
use App\Models\Massevent\Massevent;

abstract class ETLAbstract
{
    public function __construct(
        protected ImportSession $session
        , protected Massevent $massevent
        , protected string $fileName
        , protected ?array $attributes = null
    )
    {
    }

}
