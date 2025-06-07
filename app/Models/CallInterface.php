<?php

namespace App\Models;

use App\Services\Call\CallServiceInterface;

interface CallInterface
{
    public function getPhoneFrom(): string;
    public function getPhoneTo(): string;

    public function do(CallServiceInterface $service);
}
