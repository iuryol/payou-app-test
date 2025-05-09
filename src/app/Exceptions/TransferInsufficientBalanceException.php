<?php

namespace App\Exceptions;

use Exception;

class TransferInsufficientBalanceException extends Exception
{
    protected const DEFAULT_MESSAGE = 'Saldo insuficiente para concluir a transferência.';

    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}
