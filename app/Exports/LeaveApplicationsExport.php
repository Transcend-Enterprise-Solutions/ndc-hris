<?php

namespace App\Exports;

use App\Models\LeaveApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveApplicationsExport implements FromCollection, WithHeadings
{
    protected $statuses;

    public function __construct($statuses)
    {
        $this->statuses = $statuses;
    }

    public function collection()
    {
        return LeaveApplication::whereIn('status', $this->statuses)->get([
            'id',
            'date_of_filing',
            'name',
            'office_or_department',
            'position',
            'salary',
            'type_of_leave',
            'details_of_leave',
            'number_of_days',
            'list_of_dates',
            'commutation',
            'approved_dates',
            'approved_days',
            'remarks',
            'status',
        ]);
    }

    // Define the headings for the exported Excel sheet
    public function headings(): array
    {
        return [
            'Leave Application ID',
            'Date Filed',
            'Name',
            'Office/Department',
            'Position',
            'Salary',
            'Type of Leave',
            'Details of Leave',
            'Requested Days',
            'Requested Date/s',
            'Commutation',
            'Approved Date/s',
            'Approved Day/s',
            'Remarks',
            'Status',
        ];
    }
}
