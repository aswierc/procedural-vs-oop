<?php

declare(strict_types=1);

namespace Procedural\Exception;

use Exception;

class UpdatePhoneNumberException extends Exception
{
    public static function requirementsAreEmpty(): self
    {
        return new self('phone number and area code cannot be empty');
    }

    public static function phoneNumberExists(string $phoneNumber, string $areaCode): self
    {
        return new self(sprintf(
            'the phone number %s%s is assigned to another user',
            $areaCode,
            $phoneNumber
        ));
    }

    public static function undefinedUser(string $userId): self
    {
        return new self(sprintf('unable to find user by id %s', $userId));
    }

    public static function phoneAlreadyVerified(): self
    {
        return new self('phone mumber is already verified');
    }
}
