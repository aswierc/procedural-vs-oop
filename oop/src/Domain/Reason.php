<?php

declare(strict_types=1);

namespace Oop\Domain;

class Reason
{
    public function __construct(private readonly string $msg)
    {
    }

    public function getMsg(): string
    {
        return $this->msg;
    }
}
