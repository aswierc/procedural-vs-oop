<?php

declare(strict_types=1);

namespace Procedural\Controller;

use Procedural\Entity\User;
use Procedural\Exception\UpdatePhoneNumberException;
use Procedural\Service\UpdatePhoneNumberService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneController
{
    public function updateAction(
        Request $request,
        User $user, // logged user
        UpdatePhoneNumberService $service
    ): Response {
        try {
            $service->update(
                $request->get('phone_number'),
                $request->get('area_code'),
                $user->getId(),
            );

            return new Response();
        } catch (UpdatePhoneNumberException $e) {
            // log the error or do something what do you want

            return new Response(status: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
