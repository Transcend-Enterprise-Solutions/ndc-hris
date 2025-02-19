<?php

namespace App\Livewire\Admin;

use App\Exports\BIR2316Export;
use App\Models\MonthlyIncomeTax;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Bir2316Table extends Component
{
    use WithPagination;

    public $search;
    public $exportId;
    public $employee;
    public $startMonth;
    public $endMonth;
    public $year;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function mount(){
        $this->year = Carbon::parse(now())->format('Y');
    }
    public function render()
    {
        $employees = User::where('users.user_role', 'emp')
            ->join('user_data', 'user_data.user_id', 'users.id')
            ->orderBy('user_data.surname', 'ASC')
            ->when($this->search, function ($query) {
                return $query->search2(trim($this->search));
            })
            ->paginate($this->pageSize);

        return view('livewire.admin.bir2316-table', [
            'employees' => $employees,
        ]);
    }

    public function showPDF($id){
        dd($id);
    }

    public function toggleExportOption($id){
        $this->exportId = $id;
        $this->employee = User::where('users.id', $id)
                    ->join('user_data', 'user_data.user_id', 'users.id')
                    ->first();
    }

    public function exportRecord(){
        try{
            $user = User::where('users.id', $this->exportId)
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->first();
            $record = MonthlyIncomeTax::where('user_id', $this->exportId)
                    ->orderBy('start_date', 'DESC')
                    ->get();
            if($record){
                $filters = [
                    'user' => $user,
                    'record' => $record,
                    'year' => $this->year,
                    'startMonth' => $this->startMonth,
                    'endMonth' => $this->endMonth
                ];

                $exporter = new BIR2316Export($filters);
                $result = $exporter->export();

                return response()->streamDownload(function () use ($result) {
                    echo $result['content'];
                }, $result['filename']);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->exportId = null;
        $this->employee = null;
    }
}
