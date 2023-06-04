<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class PaymentException extends Exception
{
    public static function authError(): self
    {
        return new self(__('validation.custom.payment.session'));
    }

    public static function sessionError(string $message = ''): self
    {
        return new self($message ?: __('validation.custom.payment.session'));
    }

    public static function sessionActive(): self
    {
        return new self(__('validation.custom.payment.session_active'));
    }

    public function render(): Response
    {
        $status = 400;
        $message = $this->getMessage();

        return response(['error' => $message,], $status);
    }
}
