<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class CustomException extends Exception
{
    public function render(): RedirectResponse
    {
        return redirect()->back()->withErrors(['paymentMethod' => $this->getMessage()]);
    }
}
