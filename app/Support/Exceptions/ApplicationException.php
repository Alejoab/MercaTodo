<?php

namespace App\Support\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApplicationException extends CustomException
{
    protected string $sessionErrorName = 'app';

    public function __construct(Throwable $e, array $data = [])
    {
        parent::__construct("The application is not working properly. Please contact with support.");

        Log::error("[ERROR]", [
                'Data' => $data,
                'Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
                'Trace' => $e->getTraceAsString(),
            ]
        );
    }
}
