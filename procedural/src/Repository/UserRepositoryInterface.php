<?php

declare(strict_types=1);

namespace Procedural\Repository;

use Procedural\Entity\User;

interface UserRepositoryInterface
{
    public function findByPhoneNumber(string $phoneNumber, string $areaNumber): ?User;
    public function findById(string $id): ?User;
    public function save(User $user): void;
}
