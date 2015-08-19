<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

class Response
{
    protected $success;

    protected $transactionId;

    public function __construct($success, $transactionId = null)
    {
        $this->success       = $success;
        $this->transactionId = $transactionId;
    }

    public function isSuccess()
    {
        return true === $this->success;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
