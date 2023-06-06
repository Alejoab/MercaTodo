<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class PaymentException extends CustomException
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
}
