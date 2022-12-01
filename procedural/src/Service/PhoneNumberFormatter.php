<?php

declare(strict_types=1);

namespace Procedural\Service;

class PhoneNumberFormatter
{
    public function formatPhoneNumber(string $phoneNumber): string
    {
        // do some stuff with the string, remove spaces or something

        return $phoneNumber;
    }

    public function formatAreaCode(string $areaCode): string
    {
        // the same here, maybe, check if someone put here multiple ++ or spaces

        return $areaCode;
    }
}
