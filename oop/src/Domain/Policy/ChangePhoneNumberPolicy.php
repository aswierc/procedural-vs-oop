<?php

declare(strict_types=1);

namespace Oop\Domain\Policy;

use Oop\Domain\Reason;
use Oop\Domain\Result;
use Oop\Domain\ValueObject\PhoneNumber;
use Oop\Entity\User;
use Oop\Repository\UserRepositoryInterface;

class ChangePhoneNumberPolicy
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function canBeUpdated(User $user, PhoneNumber $phoneNumber): Result
    {
        $differentUser = $this->userRepository->findByPhoneNumber($phoneNumber);

        if ($differentUser instanceof User && !$differentUser->isEqual($user)) {
            return Result::failure(new Reason('the phone number is already in use'));
        }

        // ... other conditions ...

        // if the requires changed, you'll change only the policy, or make interface and you'll change your DI config

        return Result::success();
    }
}
