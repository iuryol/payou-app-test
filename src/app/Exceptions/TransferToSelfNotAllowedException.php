<?php

namespace App\Exceptions;

use Exception;

class TransferToSelfNotAllowedException extends Exception
{
    protected const DEFAULT_MESSAGE = 'Transferência para si mesmo não é permitida.';

    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}
