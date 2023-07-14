<?php

namespace App\Domain\Reports\Factories;

use App\Domain\Reports\Classes\BaseReport;
use App\Domain\Reports\Classes\SalesByBrand;
use App\Domain\Reports\Classes\SalesByCategory;
use App\Domain\Reports\Classes\SalesByDepartment;
use App\Domain\Reports\Classes\SalesByPaymentMethod;
use App\Domain\Reports\Classes\SalesByProduct;
use App\Domain\Reports\Enums\ReportType;
use Illuminate\Support\Carbon;

class ReportFactory
{
    public static function create(ReportType $report, ?Carbon $from, ?Carbon $to): BaseReport
    {
        return match ($report) {
            ReportType::R1 => new SalesByCategory($from, $to),
            ReportType::R2 => new SalesByBrand($from, $to),
            ReportType::R3 => new SalesByProduct($from, $to),
            ReportType::R4 => new SalesByPaymentMethod($from, $to),
            ReportType::R5 => new SalesByDepartment($from, $to),
        };
    }
}
