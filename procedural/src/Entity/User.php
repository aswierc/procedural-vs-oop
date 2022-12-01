<?php

declare(strict_types=1);

namespace Procedural\Entity;

use Ramsey\Uuid\Uuid;

class User
{
    private string $id;
    private ?string $phoneNumber = null;
    private ?string $phoneNumberAreaCode = null;
    private bool $phoneNumberVerified = false;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setPhoneNumberAreaCode(string $phoneNumberAreaCode): void
    {
        $this->phoneNumberAreaCode = $phoneNumberAreaCode;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getPhoneNumberAreaCode(): ?string
    {
        return $this->phoneNumberAreaCode;
    }

    public function isPhoneNumberVerified(): bool
    {
        return $this->phoneNumberVerified;
    }

    public function setPhoneNumberVerified(bool $phoneNumberVerified): void
    {
        $this->phoneNumberVerified = $phoneNumberVerified;
    }
}
