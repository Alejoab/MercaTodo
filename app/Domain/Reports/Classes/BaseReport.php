<?php

namespace App\Domain\Reports\Classes;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Mails\JobsByUserMail;
use App\Support\Models\JobsByUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseReport implements FromQuery, WithHeadings, WithTitle, ShouldQueue, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    protected JobsByUser $report;
    protected ?Carbon $from;
    protected ?Carbon $to;

    public function __construct(JobsByUser $report, ?Carbon $from, ?Carbon $to)
    {
        $this->report = $report;
        $this->from = $from;
        $this->to = $to;
    }

    public abstract function query();

    public abstract function headings(): array;

    public abstract function title(): string;

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true,],
                'alignment' => ['horizontal' => 'center',],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '8DB4E2',],
                ],
            ],
        ];
    }

    public function failed(): void
    {
        $this->report->status = JobsByUserStatus::FAILED;
        $this->report->save();

        Mail::to($this->report->user->email)->queue(new JobsByUserMail($this->report));
    }
}
