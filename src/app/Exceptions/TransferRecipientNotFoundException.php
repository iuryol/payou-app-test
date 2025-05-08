<?php

namespace App\Exceptions;

use Exception;

class TransferRecipientNotFoundException extends Exception
{
    protected const DEFAULT_MESSAGE = 'O usuário destinatário não foi encontrado. Verifique se os dados estão corretos e tente novamente.';
    public function __construct(string $message = self::DEFAULT_MESSAGE)
    {
        parent::__construct($message);
    }
}
