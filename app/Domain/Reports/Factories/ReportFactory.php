<?php

namespace App\Domain\Reports\Factories;

use App\Domain\Reports\Classes\BaseReport;
use App\Domain\Reports\Classes\SalesByBrand;
use App\Domain\Reports\Classes\SalesByCategory;
use App\Domain\Reports\Classes\SalesByDepartment;
use App\Domain\Reports\Classes\SalesByPaymentMethodAndStatus;
use App\Domain\Reports\Classes\SalesByProduct;
use App\Domain\Reports\Enums\ReportType;
use App\Support\Models\JobsByUser;
use Illuminate\Support\Carbon;

class ReportFactory
{
    public static function create(ReportType $report, JobsByUser $reportInstance, ?Carbon $from, ?Carbon $to): BaseReport
    {
        return match ($report) {
            ReportType::R1 => new SalesByCategory($reportInstance, $from, $to),
            ReportType::R2 => new SalesByBrand($reportInstance, $from, $to),
            ReportType::R3 => new SalesByProduct($reportInstance, $from, $to),
            ReportType::R4 => new SalesByPaymentMethodAndStatus($reportInstance, $from, $to),
            ReportType::R5 => new SalesByDepartment($reportInstance, $from, $to),
        };
    }
}
