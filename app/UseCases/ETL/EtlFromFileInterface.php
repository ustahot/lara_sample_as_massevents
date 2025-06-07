<?php

namespace App\UseCases\ETL;

use App\Models\ETL\ImportSession;
use App\Models\Massevent\Massevent;

interface EtlFromFileInterface
{
    public static function make(ImportSession $session, Massevent $massevent, string $fileName, ?array $attributes = null): EtlFromFileInterface;
    public function extract(): int;
    public function transform(): int;
    public function load(): int;

    public static function validated(?array $attributes = null): ?array;
}
