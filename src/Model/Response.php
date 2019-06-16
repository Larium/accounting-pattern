<?php

declare(strict_types = 1);

namespace Larium\Model;

class Response implements ResponseInterface
{
    /**
     * @var bool
     */
    protected $success;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $message;

    public function __construct(bool $success, string $transactionId = null, string $message = null)
    {
        $this->success = $success;
        $this->transactionId = $transactionId;
        $this->message = $message;
    }

    public function isSuccess(): bool
    {
        return true === $this->success;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
