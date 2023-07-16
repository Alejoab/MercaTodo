<?php

namespace App\Domain\Reports\Enums;

enum ReportType: string
{
    case R1 = 'Sales by category';
    case R2 = 'Sales by brand';
    case R3 = 'Sales by product';
    case R4 = 'Orders by payment method and status';
    case R5 = 'Orders by department';
}
