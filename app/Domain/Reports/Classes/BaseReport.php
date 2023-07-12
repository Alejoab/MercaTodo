<?php

namespace App\Domain\Reports\Classes;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseReport implements FromQuery, WithHeadings, WithTitle, ShouldQueue, ShouldAutoSize, WithStyles
{

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
}
