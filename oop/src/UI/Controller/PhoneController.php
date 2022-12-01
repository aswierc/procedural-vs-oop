<?php

declare(strict_types=1);

namespace Oop\Controller;

use Oop\Domain\Policy\ChangePhoneNumberPolicy;
use Oop\Domain\ValueObject\PhoneNumber;
use Oop\Entity\User;
use Oop\EventDispatcher;
use Oop\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneController
{
    public function __construct(private readonly EventDispatcher $eventDispatcher)
    {
    }

    /**
     *  @todo
     *  you can move the logic to an application service,
     *  to make the internal API and keep thin the controller
     */
    public function updateAction(
        Request $request,
        User $user, // logged user
        ChangePhoneNumberPolicy $policy,
        UserRepositoryInterface $repository
    ): Response {
        $result = $user->updatePhoneNumber(new PhoneNumber(
            $request->get('area_code'),
            $request->get('phone_number')
        ), $policy);

        $repository->save($user);

        if ($result->isFailure()) {
            return new JsonResponse(
                [
                    'error' => $result->reason()->getMsg(),
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $domainEvent = $result->event();
        if (null !== $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return new JsonResponse();
    }
}
