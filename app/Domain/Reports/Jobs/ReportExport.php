<?php

namespace App\Domain\Reports\Jobs;

use App\Domain\Reports\Enums\ReportType;
use App\Domain\Reports\Factories\ReportFactory;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Models\JobsByUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets, ShouldQueue
{
    private JobsByUser $report;
    private array $reports;
    private ?Carbon $from;
    private ?Carbon $to;

    public function __construct(JobsByUser $report, array $reports, ?Carbon $from, ?Carbon $to)
    {
        $this->report = $report;
        $this->reports = $reports;
        $this->from = $from;
        $this->to = $to;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->reports as $report) {
            $report = ReportType::from($report);
            $sheets[] = ReportFactory::create($report, $this->from, $this->to);
        }

        return $sheets;
    }

    public function failed(): void
    {
        $this->report->status = JobsByUserStatus::FAILED;
        $this->report->save();
    }
}
