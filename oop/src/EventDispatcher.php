<?php

declare(strict_types=1);

namespace Oop;

use Oop\Domain\DomainEvent;

interface EventDispatcher
{
    public function dispatch(DomainEvent $event): void;
}
