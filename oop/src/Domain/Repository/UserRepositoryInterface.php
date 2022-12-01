<?php

declare(strict_types=1);

namespace Oop\Repository;

use Oop\Domain\ValueObject\PhoneNumber;
use Oop\Entity\User;

interface UserRepositoryInterface
{
    public function findByPhoneNumber(PhoneNumber $phoneNumber): ?User;
    public function findById(string $id): ?User;
    public function save(User $user): void;
}
