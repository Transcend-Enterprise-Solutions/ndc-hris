<?php

namespace App\Livewire\Admin\PayrollComponent;

use App\Exports\GeneralPayrollExport;
use App\Models\PlantillaPayslip;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;

class PlantillaRecordedPayroll extends Component
{
    public $pageSize = 30; 
    public $pageSizes = [10, 20, 30, 50, 100]; 
    public $recordMonth;

    public function render()
    {
        $releasedPayrolls = PlantillaPayslip::select('start_date', 'end_date')
                    ->when($this->recordMonth, function ($query) {
                        $query->whereMonth('start_date', Carbon::parse($this->recordMonth))
                        ->whereYear('start_date', Carbon::parse($this->recordMonth));
                    })
                    ->groupBy('start_date', 'end_date')
                    ->paginate($this->pageSize);

        return view('livewire.admin.payroll-component.plantilla-recorded-payroll', [
            'releasedPayrolls' => $releasedPayrolls,
        ]);
    }

    public function exportExcel($startMonth, $endMonth){
        $signatories = User::join('signatories', 'signatories.user_id', 'users.id')
            ->join('positions', 'positions.id', 'users.position_id')
            ->where('signatories.signatory_type', 'plantilla_payroll')
            ->select('users.name', 'positions.position', 'signatories.*');
    
        $startDate = Carbon::parse($startMonth);
        $endDate = Carbon::parse($endMonth);
    
        $fileName = 'General Payroll ' . $startDate->format('F Y');
        if ($startDate->format('Y-m') !== $endDate->format('Y-m')) {
            $fileName .= ' to ' . $endDate->format('F Y');
        }
        $fileName .= '.xlsx';

        try {
            $filters = [
                'search' => null,
                'startMonth' => $startDate->format('F Y'),
                'endMonth' => $endDate->format('F Y'),
                'signatories' => $signatories,
            ];

            $exporter = new GeneralPayrollExport($filters);
            $result = $exporter->export();

            return response()->streamDownload(function () use ($result) {
                echo $result['content'];
            }, $result['filename']);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
