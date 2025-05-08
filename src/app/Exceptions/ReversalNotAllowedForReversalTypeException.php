<?php

namespace App\Exceptions;

use Exception;

class ReversalNotAllowedForReversalTypeException extends Exception
{
    protected const DEFAULT_MESSAGE = 'Transações do tipo reversão não podem ser revertidas novamente.';

    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}
