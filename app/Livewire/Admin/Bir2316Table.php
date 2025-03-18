<?php

namespace App\Livewire\Admin;

use App\Exports\BIR2316Export;
use App\Models\MonthlyIncomeTax;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use setasign\Fpdi\Fpdi;

class Bir2316Table extends Component
{
    use WithPagination;

    public $search;
    public $exportId;
    public $employee;
    public $startMonth;
    public $endMonth;
    public $startDate;
    public $employeeName;
    public $endDate;
    public $pdfContent;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function mount(){
        $this->startDate = Carbon::create(now()->year, 1, 1);
        $this->endDate = Carbon::create(now()->year, 12, 31);
    }

    public function render()
    {
        $this->showPDF(79);
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

    public function showPDF($id)
    {
        $templatePath = storage_path('app/templates/bir2316.pdf');
    
        if (!file_exists($templatePath)) {
            return $this->dispatch('swal', [
                'title' => 'Template not found',
                'icon' => 'error'
            ]);
        }
        
        $employee = User::where('users.id', $id)
                    ->join('user_data', 'user_data.user_id', 'users.id')
                    ->first();
        
        if (!$employee) {
            return $this->dispatch('swal', [
                'title' => 'Employee not found',
                'icon' => 'error'
            ]);
        }

        $outputPath = storage_path('app/temp/bir2316_' . $id . '_' . time() . '.pdf');
        $pdf = new Fpdi();
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pageCount = $pdf->setSourceFile($templatePath);



        $fullname = $employee->surname . ', ' . $employee->first_name . ', ' . ($employee->middle_name ?: '');
        $this->employeeName = $fullname;
        $address = $employee->p_house_street . ' ' . $employee->permanent_selectedBarangay . ' ' . $employee->permanent_selectedCity . ' ' . $employee->permanent_selectedProvince;
        $address = mb_convert_case($address, MB_CASE_TITLE, "UTF-8");
        
        $year = str_split(Carbon::parse($this->startDate)->format('Y'));
        $fromMonth = str_split(Carbon::parse($this->startDate)->format('m'));
        $toMonth = str_split(Carbon::parse($this->endDate)->format('m'));
        $fromDay = str_split(Carbon::parse($this->startDate)->format('d'));
        $toDay = str_split(Carbon::parse($this->endDate)->format('d'));


        $tin = str_split(implode(explode('-', $employee->tin)));
        $zipCode = str_split($employee->permanent_selectedZipcode ?? '');
        $birthDay = str_split(Carbon::parse($employee->date_of_birth)->format('mdY'));
        $mobileNum = str_split($employee->mobile_number ?? '');

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $template = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($template);
            $pdf->AddPage('P', [$size['width'], $size['height']]);
            $pdf->useTemplate($template, 0, 0, $size['width'], $size['height'], true);
            
            $pdf->SetFont('times', '', 10);
            // $pdf->SetTextColor(0, 0, 0);
            $pdf->SetTextColor(0, 0, 0);
            
            if ($pageNo == 1) {
                // For the Year -------------------------------------------- //
                $pdf->Text(46, 37.5, $year[0] ?? 'X');
                $pdf->Text(52.5, 37.5, $year[1] ?? 'X');
                $pdf->Text(59, 37.5, $year[2] ?? 'X');
                $pdf->Text(65, 37.5, $year[3] ?? 'X');

                // For the Period From ------------------------------------- //
                $pdf->Text(138.5, 37.5, $fromMonth[0] ?? 'X');
                $pdf->Text(145, 37.5, $fromMonth[1] ?? 'X');
                $pdf->Text(151, 37.5, $fromDay[0] ?? 'X');
                $pdf->Text(157, 37.5, $fromDay[1] ?? 'X');

                // For the Period To -------------------------------------- //
                $pdf->Text(183, 37.5, $toMonth[0] ?? 'X');
                $pdf->Text(189, 37.5, $toMonth[1] ?? 'X');
                $pdf->Text(195.5, 37.5, $toDay[0] ?? 'X');
                $pdf->Text(201.5, 37.5, $toDay[1] ?? 'X');

                // Employee TIN ------------------------------------------- //
                $pdf->Text(32, 47.5, $tin[0] ?? 'X');
                $pdf->Text(36.5, 47.5, $tin[1] ?? 'X');
                $pdf->Text(40.6, 47.5, $tin[2] ?? 'X');
                $pdf->Text(49.5, 47.5, $tin[3] ?? 'X');
                $pdf->Text(54, 47.5, $tin[4] ?? 'X');
                $pdf->Text(58.3, 47.5, $tin[5] ?? 'X');
                $pdf->Text(67, 47.5, $tin[6] ?? 'X');
                $pdf->Text(71.5, 47.5, $tin[7] ?? 'X');
                $pdf->Text(76, 47.5, $tin[8] ?? 'X');
                $pdf->Text(84.5, 47.5, $tin[9] ?? '0');
                $pdf->Text(89.5, 47.5, $tin[10] ?? '0');
                $pdf->Text(95, 47.5, $tin[11] ?? '0');
                $pdf->Text(100, 47.5, $tin[12] ?? '0');
                $pdf->Text(105, 47.5, $tin[13] ?? '0');
                


                $pdf->Text(16, 56.2, $fullname);
                $pdf->Text(16, 66.2, $address);

                // Employee Zip Code -------------------------------------- //
                $pdf->Text(93.5, 66.2, $zipCode[0] ?? 'X');
                $pdf->Text(98, 66.2, $zipCode[1] ?? 'X');
                $pdf->Text(102.5, 66.2, $zipCode[2] ?? 'X');
                $pdf->Text(106.5, 66.2, $zipCode[3] ?? 'X');

                // Brthday ----------------------------------------------- //
                $pdf->Text(17.9, 94, $birthDay[0] ?? 'X');
                $pdf->Text(22.5, 94, $birthDay[1] ?? 'X');
                $pdf->Text(27, 94, $birthDay[2] ?? 'X');
                $pdf->Text(31.5, 94, $birthDay[3] ?? 'X');
                $pdf->Text(35.5, 94, $birthDay[4] ?? 'X');
                $pdf->Text(40.3, 94, $birthDay[5] ?? 'X');
                $pdf->Text(44.5, 94, $birthDay[6] ?? 'X');
                $pdf->Text(49.2, 94, $birthDay[7] ?? 'X');

                // Mobile Number ----------------------------------------- //
                $pdf->Text(60.5, 94, $mobileNum[0] ?? '');
                $pdf->Text(65, 94, $mobileNum[1] ?? '');
                $pdf->Text(69.5, 94, $mobileNum[2] ?? '');
                $pdf->Text(74, 94, $mobileNum[3] ?? '');
                $pdf->Text(78.7, 94, $mobileNum[4] ?? '');
                $pdf->Text(83.4, 94, $mobileNum[5] ?? '');
                $pdf->Text(87.8, 94, $mobileNum[6] ?? '');
                $pdf->Text(92.2, 94, $mobileNum[7] ?? '');
                $pdf->Text(96.8, 94, $mobileNum[8] ?? '');
                $pdf->Text(101, 94, $mobileNum[9] ?? '');
                $pdf->Text(106, 94, $mobileNum[10] ?? '');

                // Employer TIN ------------------------------------------- //
                $pdf->Text(32, 122.5, '0');
                $pdf->Text(36.5, 122.5, '0');
                $pdf->Text(40.6, 122.5, '0');
                $pdf->Text(49.5, 122.5, '1');
                $pdf->Text(54, 122.5, '6');
                $pdf->Text(58.3, 122.5, '4');
                $pdf->Text(67, 122.5, '1');
                $pdf->Text(71.5, 122.5, '2');
                $pdf->Text(76, 122.5, '0');
                $pdf->Text(84.5, 122.5, '0');
                $pdf->Text(89.5, 122.5, '0');
                $pdf->Text(95, 122.5, '0');
                $pdf->Text(100, 122.5, '0');
                $pdf->Text(105, 122.5, '0');

                $pdf->Text(16, 131.5, 'NATIONAL DEVELOPMENT COMPANY');

                $pdf->SetFont('times', '', 8);
                $pdf->Text(16, 140.6, '116 TORDESILLAS ST. SALCEDO VILLAGE, MAKATI');

                $pdf->SetFont('times', '', 10);

                // Employer Zip Code -------------------------------------- //
                $pdf->Text(92.8, 140.6, '1');
                $pdf->Text(97, 140.6, '2');
                $pdf->Text(101.5, 140.6, '2');
                $pdf->Text(105.7, 140.6, '7');

                $pdf->SetFont('ZapfDingbats', '', 10);
                $pdf->Text(42.2, 146.5, chr(51));

                $pdf->SetFont('times', '', 10);

                // Previous Employer -------------------------------------- //

                // Previous Employer TIN ---------------------------------- //
                $pdf->Text(31.5, 156.5, 'X');
                $pdf->Text(36, 156.5, 'X');
                $pdf->Text(40.3, 156.5, 'X');
                $pdf->Text(49.2, 156.5, 'X');
                $pdf->Text(53.5, 156.5, 'X');
                $pdf->Text(58, 156.5, 'X');
                $pdf->Text(66.6, 156.5, 'X');
                $pdf->Text(71, 156.5, 'X');
                $pdf->Text(75.5, 156.5, 'X');
                $pdf->Text(84.5, 156.5, '0');
                $pdf->Text(89.5, 156.5, '0');
                $pdf->Text(95, 156.5, '0');
                $pdf->Text(100, 156.5, '0');
                $pdf->Text(105, 156.5, '0');

                $pdf->Text(16, 165.5, 'TTTT');
            }
        }
        
        $pdf->output($outputPath, 'F');
        $pdfContent = base64_encode(file_get_contents($outputPath));
        unlink($outputPath);
        
        $this->pdfContent = $pdfContent;
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

    public function closeBIR2316(){
        $this->pdfContent = null;
    }

    public function resetVariables(){
        $this->exportId = null;
        $this->employee = null;
    }
}
