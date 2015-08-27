<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

interface ResponseInterface
{
    public function isSuccess();

    public function getTransactionId();

    public function getMessage();
}
