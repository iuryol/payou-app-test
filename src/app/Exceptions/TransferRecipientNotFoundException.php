<?php

namespace App\Exceptions;

use Exception;

class TransferRecipientNotFoundException extends Exception
{
    protected const DEFAULT_MESSAGE = 'Destinatário não foi encontrado. Verifique os dados e tente novamente.';
    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}
