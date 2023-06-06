<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CustomException extends Exception
{
    public function render(): Response
    {
        $status = 400;
        $message = $this->getMessage();

        return response(['error' => $message,], $status);
    }
}
