<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class CustomException extends Exception
{
    protected string $sessionErrorName;

    public function render(): RedirectResponse
    {
        return redirect()->back()->withErrors([$this->sessionErrorName => $this->getMessage()]);
    }
}
