<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApplicationException extends Exception
{
    public function __construct(Throwable $e)
    {
        parent::__construct("The application is not working properly. Please contact with support.");

        Log::error(
            "
        [ERROR] Message: {$e->getMessage()}
        File: {$e->getFile()}
        Line: {$e->getLine()}
        Trace: {$e->getTraceAsString()}
        "
        );
    }

    public function render(): RedirectResponse
    {
        return redirect()->back()->withErrors(['app' => $this->getMessage()]);
    }
}
