<?php

namespace App\Exports;

use App\Models\LeaveCredits;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LeaveCreditsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
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
            $columns[] = 'vl_claimed_credits';
        }

        if (in_array('Sick Leave', $this->selectedLeaveTypes)) {
            $columns[] = 'sl_claimable_credits';
            $columns[] = 'sl_claimed_credits';
        }

        if (in_array('Special Privilege Leave', $this->selectedLeaveTypes)) {
            $columns[] = 'spl_claimable_credits';
            $columns[] = 'spl_claimed_credits';
        }

        return LeaveCredits::select($columns)->get();
    }

    public function headings(): array
    {
        $headings = ['User ID'];

        if (in_array('Vacation Leave', $this->selectedLeaveTypes)) {
            $headings[] = 'Claimable Credits (VL)';
            $headings[] = 'Claimed Credits (VL)';
        }

        if (in_array('Sick Leave', $this->selectedLeaveTypes)) {
            $headings[] = 'Claimable Credits (SL)';
            $headings[] = 'Claimed Credits (SL)';
        }

        if (in_array('Special Privilege Leave', $this->selectedLeaveTypes)) {
            $headings[] = 'Claimable Credits (SPL)';
            $headings[] = 'Claimed Credits (SPL)';
        }

        return $headings;
    }

    public function map($row): array
    {
        $mappedData = [$row->user_id]; // Default data

        if (in_array('Vacation Leave', $this->selectedLeaveTypes)) {
            $mappedData[] = number_format($row->vl_claimable_credits ?? 0, 3, '.', '');
            $mappedData[] = number_format($row->vl_claimed_credits ?? 0, 3, '.', '');
        }

        if (in_array('Sick Leave', $this->selectedLeaveTypes)) {
            $mappedData[] = number_format($row->sl_claimable_credits ?? 0, 3, '.', '');
            $mappedData[] = number_format($row->sl_claimed_credits ?? 0, 3, '.', '');
        }

        if (in_array('Special Privilege Leave', $this->selectedLeaveTypes)) {
            $mappedData[] = number_format($row->spl_claimable_credits ?? 0, 3, '.', '');
            $mappedData[] = number_format($row->spl_claimed_credits ?? 0, 3, '.', '');
        }

        return $mappedData;
    }

    public function columnFormats(): array
    {
        return [
            'B' => '#,##0.000',
            'C' => '#,##0.000',
            'D' => '#,##0.000',
            'E' => '#,##0.000',
            'F' => '#,##0.000',
            'G' => '#,##0.000',
        ];
    }
}
