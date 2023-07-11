<?php

namespace App\Http\Controllers\Web\Reports;

use App\Domain\Reports\Enums\ReportType;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AdminReportController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Administrator/Reports/Index', [
            'reportTypes' => ReportType::cases(),
        ]);
    }
}
