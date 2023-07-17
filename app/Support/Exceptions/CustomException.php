<?php

namespace App\Support\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CustomException extends Exception
{
    protected string $sessionErrorName;

    /**
     * @throws ValidationException
     */
    public function render()
    {
        throw ValidationException::withMessages([$this->sessionErrorName => $this->getMessage()]);
    }
}
