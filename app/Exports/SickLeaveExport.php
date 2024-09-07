<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SickLeaveExport implements FromQuery, WithHeadings, WithMapping
{
    protected $statusesForSL;

    public function __construct($statusesForSL)
    {
        $this->statusesForSL = $statusesForSL;
    }

    public function query()
    {
        return LeaveApplication::query()
            ->where('type_of_leave', 'LIKE', '%Sick Leave%')
            ->whereIn('status', $this->statusesForSL);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date Filed',
            'Name',
            'Office/Department',
            'Position',
            'Salary',
            'Type of Leave',
            'Details of Leave',
            'Requested Day/s',
            'Requested Date/s',
            'Commutation',
            'Approved Date/s',
            'Approved Day/s',
            'Remarks',
            'Status',
        ];
    }

    public function map($leaveApplication): array
    {
        return [
            $leaveApplication->id,
            $leaveApplication->date_of_filing,
            $leaveApplication->name,
            $leaveApplication->office_or_department,
            $leaveApplication->position,
            $leaveApplication->salary,
            $leaveApplication->type_of_leave,
            $leaveApplication->details_of_leave,
            $leaveApplication->number_of_days,
            $leaveApplication->list_of_dates,
            $leaveApplication->commutation,
            $leaveApplication->approved_dates ?? 'N/A',
            $leaveApplication->approved_days !== null ? ($leaveApplication->approved_days === 0 ? '0' : $leaveApplication->approved_days) : 'N/A',
            $leaveApplication->remarks ?? 'N/A',
            $leaveApplication->status,
        ];
    }
}
