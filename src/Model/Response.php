<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

class Response implements ResponseInterface
{
    protected $success;

    protected $transactionId;

    protected $message;

    public function __construct($success, $transactionId = null, $message = null)
    {
        $this->success       = $success;
        $this->transactionId = $transactionId;
        $this->message       = $message;
    }

    public function isSuccess()
    {
        return true === $this->success;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
