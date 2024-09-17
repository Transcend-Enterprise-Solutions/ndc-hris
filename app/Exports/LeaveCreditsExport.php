<?php

namespace App\Exports;

use App\Models\LeaveCredits;
use App\Models\User; // Import User model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LeaveCreditsExport implements FromCollection, WithMapping, WithColumnFormatting, WithEvents
{
    protected $selectedLeaveTypes;

    public function __construct(array $selectedLeaveTypes)
    {
        $this->selectedLeaveTypes = $selectedLeaveTypes;
    }

    public function collection()
    {
        $columns = ['user_id'];

        if (in_array('Vacation Leave', $this->selectedLeaveTypes)) {
            $columns[] = 'vl_claimable_credits';
        }

        if (in_array('Sick Leave', $this->selectedLeaveTypes)) {
            $columns[] = 'sl_claimable_credits';
        }

        if (in_array('Special Privilege Leave', $this->selectedLeaveTypes)) {
            $columns[] = 'spl_claimable_credits';
        }

        return LeaveCredits::select($columns)->get();
    }

    public function map($row): array
    {
        $user = User::find($row->user_id);

        $vlClaimable = number_format($row->vl_claimable_credits ?? 0, 3, '.', '');
        $slClaimable = number_format($row->sl_claimable_credits ?? 0, 3, '.', '');
        $splClaimable = number_format($row->spl_claimable_credits ?? 0, 3, '.', '');
        $totalClaimable = number_format(($row->vl_claimable_credits ?? 0) + ($row->sl_claimable_credits ?? 0), 3, '.', '');

        return [
            $user ? $user->name : 'N/A', // User Name
            $user ? $user->emp_code : 'N/A', // Employee ID
            $vlClaimable, // VL Claimable Credits
            $slClaimable, // SL Claimable Credits
            $totalClaimable, // Total Claimable Credits
            $splClaimable // SPL Claimable Credits
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '#,##0.000',
            'D' => '#,##0.000',
            'E' => '#,##0.000',
            'F' => '#,##0.000',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge and style header cells A1 to A4
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_BOTTOM,
                    ],
                ]);
                $sheet->setCellValue('A1', 'REPORT ON LEAVE CREDITS');

                $sheet->mergeCells('A2:F2');
                $sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_BOTTOM,
                    ],
                ]);
                $sheet->setCellValue('A2', '');

                // Set column headers in row 4
                $headers = [
                    'A3' => 'NAME',
                    'B3' => 'EMPLOYEE ID',
                    'C3' => 'VL',
                    'D3' => 'SL',
                    'E3' => 'Total',
                    'F3' => 'SPL',
                ];

                foreach ($headers as $cell => $header) {
                    $sheet->setCellValue($cell, $header);
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 12,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['argb' => '9bc2e6'],
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => '000000'],
                            ],
                            'inside' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                }

                // Start inserting records from row 4
                $rowIndex = 4;
                foreach ($this->collection() as $row) {
                    $data = $this->map($row);
                    foreach ($data as $colIndex => $value) {
                        $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex, $value);
                    }
                    $rowIndex++;
                }

                // Center all cells and wrap text for specific columns
                $sheet->getStyle('A4:F' . $sheet->getHighestRow())->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('C4:F' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);

                // Set default column widths
                $sheet->getColumnDimension('A')->setWidth(35); // Adjusted width for User Name
                $sheet->getColumnDimension('B')->setWidth(20); // Adjusted width for Employee ID
                $sheet->getColumnDimension('C')->setWidth(15); // Width for VL Claimable Credits
                $sheet->getColumnDimension('D')->setWidth(15); // Width for SL Claimable Credits
                $sheet->getColumnDimension('E')->setWidth(15); // Width for Total Claimable Credits
                $sheet->getColumnDimension('F')->setWidth(15); // Width for SPL Claimable Credits
            },
        ];
    }
}
