<?php

namespace App\Domain\Reports\Jobs;

use App\Domain\Reports\Enums\ReportType;
use App\Domain\Reports\Factories\ReportFactory;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Mails\JobsByUserMail;
use App\Support\Models\JobsByUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ReportExport implements WithMultipleSheets, ShouldQueue, WithStrictNullComparison
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

        foreach ($this->reports as $reportType) {
            $reportType = ReportType::from($reportType);
            $sheets[] = ReportFactory::create($reportType, $this->report, $this->from, $this->to);
        }

        return $sheets;
    }

    public function failed(): void
    {
        $this->report->status = JobsByUserStatus::FAILED;
        $this->report->save();

        Mail::to($this->report->user->email)->queue(new JobsByUserMail($this->report));
    }
}
