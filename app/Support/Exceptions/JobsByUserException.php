<?php

namespace App\Support\Exceptions;

use App\Support\Enums\JobsByUserType;

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

    public static function salesActive(): self
    {
        return new self(trans('validation.custom.jobsByUser.sales_active'), 'sales');
    }

    public static function createFromType(JobsByUserType $type): self
    {
        return match ($type) {
            JobsByUserType::REPORT => self::reportActive(),
            JobsByUserType::IMPORT => self::importActive(),
            JobsByUserType::EXPORT => self::exportActive(),
            JobsByUserType::SALES => self::salesActive(),
        };
    }
}
