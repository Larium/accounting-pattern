<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

class Response
{
    protected $success;

    public function __construct($success)
    {
        $this->success = $success;
    }

    public function isSuccess()
    {
        return true === $this->success;
    }
}
