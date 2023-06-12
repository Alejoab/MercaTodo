<?php

namespace App\Exceptions;

class PaymentException extends CustomException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->sessionErrorName = 'payment';
    }

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

    public static function orderNotActive(): self
    {
        return new self(__('validation.custom.payment.session_expired'));
    }

    public static function orderNotFound(): self
    {
        return new self(__('validation.custom.payment.order_not_found'));
    }
}
