<?php

declare(strict_types=1);

namespace Oop\Domain\ValueObject;

class PhoneNumber
{
    public function __construct(
        private readonly string $areaCode,
        private readonly string $number
    ) {
    }

    public function toString(): string
    {
        return $this->areaCode . str_replace(' ', '', $this->number);
    }
}
