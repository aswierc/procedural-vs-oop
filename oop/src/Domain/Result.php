<?php

declare(strict_types=1);

namespace Oop\Domain;

class Result
{
    private function __construct(
        private readonly bool $isFailure,
        private readonly ?DomainEvent $event = null,
        private readonly ?Reason $reason = null
    ) {
    }

    public static function failure(Reason $reason): self
    {
        return new self(true, null, $reason);
    }

    public static function success(?DomainEvent $event = null): self
    {
        return new self(false, $event, null);
    }

    public function isFailure(): bool
    {
        return $this->isFailure;
    }

    public function reason(): ?Reason
    {
        return $this->reason;
    }

    public function event(): ?DomainEvent
    {
        return $this->event;
    }
}
