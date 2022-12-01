<?php

declare(strict_types=1);

namespace Procedural\Service;

use Procedural\Exception\UpdatePhoneNumberException;
use Procedural\Repository\UserRepositoryInterface;

class UpdatePhoneNumberService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PhoneNumberFormatter $formatter
    ) {
    }

    /**
     * @throws UpdatePhoneNumberException
     */
    public function update(string $phoneNumber, string $areaCode, string $userId): void
    {
        $phoneNumber = $this->formatter->formatPhoneNumber($phoneNumber);
        $areaCode = $this->formatter->formatAreaCode($areaCode);

        if (empty($areaCode) || empty($phoneNumber)) {
            throw UpdatePhoneNumberException::requirementsAreEmpty();
        }

        $differentUser = $this->userRepository->findByPhoneNumber($phoneNumber, $areaCode);

        if (null !== $differentUser && $differentUser->getId() === $userId) {
            throw UpdatePhoneNumberException::phoneNumberExists($phoneNumber, $areaCode);
        }

        $user = $this->userRepository->findById($userId);

        if (null === $user) {
            throw UpdatePhoneNumberException::undefinedUser($userId);
        }

        if ($user->isPhoneNumberVerified()) {
            throw UpdatePhoneNumberException::phoneAlreadyVerified();
        }

        $user->setPhoneNumber($phoneNumber);
        $user->setPhoneNumberAreaCode($areaCode);

        $this->userRepository->save($user);
    }
}
