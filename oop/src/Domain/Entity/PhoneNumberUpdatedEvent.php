<?php

declare(strict_types=1);

namespace Oop\Entity;

use Oop\Domain\DomainEvent;
use Oop\Domain\ValueObject\PhoneNumber;

class PhoneNumberUpdatedEvent implements DomainEvent
{
    public function __construct(
        public readonly string $userId,
        public readonly PhoneNumber $phoneNumber
    ) {
    }
}
