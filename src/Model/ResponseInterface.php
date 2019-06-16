<?php

declare(strict_types = 1);

namespace Larium\Model;

interface ResponseInterface
{
    public function isSuccess(): bool;

    public function getTransactionId(): ?string;

    public function getMessage(): ?string;
}
