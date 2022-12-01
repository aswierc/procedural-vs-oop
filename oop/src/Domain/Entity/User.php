<?php

declare(strict_types=1);

namespace Oop\Entity;

use Oop\Domain\Policy\ChangePhoneNumberPolicy;
use Oop\Domain\Result;
use Oop\Domain\ValueObject\PhoneNumber;
use Ramsey\Uuid\Uuid;

class User
{
    private string $id;
    private PhoneNumber $phoneNumber;
    private bool $phoneNumberVerified = false;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function updatePhoneNumber(PhoneNumber $phoneNumber, ChangePhoneNumberPolicy $policy): Result
    {
        $result = $policy->canBeUpdated($this, $phoneNumber);

        if ($result->isFailure()) {
            return $result;
        }

        $this->phoneNumber = $phoneNumber;

        return Result::success(new PhoneNumberUpdatedEvent($this->getId(), $phoneNumber));
    }

    public function verify(string $verifyCode, $policy): Result
    {
        // todo
    }

    public function isEqual(User $user): bool
    {
        return $this->id === $user->getId();
    }
}
