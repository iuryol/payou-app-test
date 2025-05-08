<?php

namespace App\Exceptions;

use Exception;

class ReversalNotAllowedForIncompleteTransactionException extends Exception
{
    protected const DEFAULT_MESSAGE = 'Transações não concluídas não podem ser revertidas.';

    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}
