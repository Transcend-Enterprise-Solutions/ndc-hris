<?php

namespace App\Livewire\User;

use App\Exports\PayrollExport;
use App\Models\EmployeesDtr;
use App\Models\EmployeesPayroll;
use App\Models\GeneralPayroll;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PayslipTable extends Component
{
    public $date;
    public $startDate;
    public $endDate;
    public $monthsEnd;
    public $range;
    protected $payrolls = [];
    public $search;
    public $columns = [
        'name',
        'employee_number',
        'position',
        'salary_grade',
        'daily_salary_rate',
        'no_of_days_covered',
        'gross_salary',
        'absences_days',
        'absences_amount',
        'late_undertime_hours',
        'late_undertime_hours_amount',
        'late_undertime_mins',
        'late_undertime_mins_amount',
        'gross_salary_less',
        'withholding_tax',
        'nycempc',
        'total_deductions',
        'net_amount_due',
    ];

    public function render(){
        $user = Auth::user();

        if ($this->date) {
            // Create a Carbon instance from the month input
            $carbonDate = Carbon::createFromFormat('Y-m', $this->date);
    
            // Set the date ranges for the first and second halves of the month
            $startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
            $endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
            $startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
            $endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();
            $this->monthsEnd = $endDateSecondHalf;

            if($this->range){
                switch($this->range){
                    case 1:
                        $this->startDate = $startDateFirstHalf;
                        $this->endDate = $endDateFirstHalf;
                        break;
                    case 2:
                        $this->startDate = $startDateSecondHalf;
                        $this->endDate = $endDateSecondHalf;
                        break;
                    default:
                        break;
                }
            }

            $query = EmployeesPayroll::where('user_id', $user->id)
                ->where('start_date', $this->startDate)
                ->where('end_date', $this->endDate)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                });
    
            if ($query->exists()) {
                $this->payrolls = $query->paginate(10);
            }
        }else{
            $this->payrolls = EmployeesPayroll::where('user_id', $user->id)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })->paginate(10);
        }

        return view('livewire.user.payslip-table', [
            'payrolls' => $this->payrolls,
        ]);
    }

    public function exportPayslip($id){
        try{
            $payroll = EmployeesPayroll::where('id', $id)->first();
            $pds = $this->pds;
            $pdf = Pdf::loadView('pdf.pds', ['pds' => $pds]);
            $pdf->setPaper('A4', 'portrait');
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, $pds['userData']->first_name . ' ' . $pds['userData']->surname . ' PDS.pdf');
        }catch(Exception $e){
            throw $e;
        }
    }
}
