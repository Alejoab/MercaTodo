<?php

namespace App\Support\Exceptions;

class JobsByUserException extends CustomException
{
    public function __construct(string $message, string $name)
    {
        parent::__construct($message);
        $this->sessionErrorName = $name;
    }

    public static function reportActive(): self
    {
        return new self(trans('validation.custom.jobsByUser.report_active'), 'report');
    }

    public static function importActive(): self
    {
        return new self(trans('validation.custom.jobsByUser.import_active'), 'import');
    }

    public static function exportActive(): self
    {
        return new self(trans('validation.custom.jobsByUser.export_active'), 'export');
    }
}
