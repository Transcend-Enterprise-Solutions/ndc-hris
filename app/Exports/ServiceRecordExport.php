<?php

namespace App\Exports;

use App\Models\Payrolls;
use App\Models\PayrollsLeaveCreditsDeduction;
use App\Models\User;
use Exception;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SebastianBergmann\Diff\Chunk;

class ServiceRecordExport
{
    use Exportable;

    protected $filters;
    protected $currentRow = 14;

    public function __construct($filters){
        $this->filters = $filters;
    }

    private function getMonthsRange(){
        $start = Carbon::parse($this->filters['startMonth']);
        $end = Carbon::parse($this->filters['endMonth']);
        $months = [];

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        return $months;
    }

    public function export(){
        try {
            $spreadsheet = IOFactory::load(storage_path('app/templates/service_record_template.xlsx'));
            $sheet = $spreadsheet->getSheetByName(worksheetName: 'Service Record');

            $record = $this->filters['record'];
            $user = $this->filters['user'];
            $this->addData($sheet, $record, $user);

            $writer = new Xlsx($spreadsheet);
            $filename = $this->filters['user']->name . "_ServiceRecord.xlsx";

            $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
            $writer->save($tempFile);
            $fileContent = file_get_contents($tempFile);
            unlink($tempFile);
            return [
                'content' => $fileContent,
                'filename' => $filename
            ];
        }catch(Exception $e){
            throw $e;
        }
    }

    protected function addData($sheet, $record, $user){
        $sheet->setCellValue("C{$this->currentRow}", $user->userData->surname);
        $sheet->setCellValue("E{$this->currentRow}", $user->userData->first_name);
        $sheet->setCellValue("H{$this->currentRow}", $user->userData->middle_name);
        $sheet->setCellValue("J{$this->currentRow}", "");

        $this->currentRow +=3;
        $sheet->setCellValue("C{$this->currentRow}", Carbon::parse($user->userData->date_of_birth)->format('F d, Y'));
        $sheet->setCellValue("F{$this->currentRow}", $user->userData->place_of_birth);
        $sheet->setCellValue("J{$this->currentRow}", $user->userData->sex == 'No' ? 'Prefer Not To Say' : $user->userData->sex);

        $status = '';
        if($user->userData->civil_status == 'Single'){
            $status = 'S';
        }elseif($user->userData->civil_status == 'Married'){
            $status = 'M';
        }elseif($user->userData->civil_status == 'Widowed'){
            $status = 'W';
        }elseif($user->userData->civil_status == 'Separated'){
            $status = 'S';
        }
        $sheet->setCellValue("K{$this->currentRow}", 'Civil Status :   '. $status);

        $this->addDataRows($sheet, $record, $user);
    }

    protected function addDataRows($sheet, $record, $user)
    {
        $formatDate = function($value) {
            return Carbon::parse($value)->format('m/d/y');
        };

        $this->currentRow = 28;
        $numberOfData = 0;
        $pageNumber = 1;
        $totalRecords = count($record);

        $pageStartRows = [28, 81, 136, 191, 246]; // Starting rows for each page

        foreach ($record as $index => $data) {
            $sheet->setCellValue("B{$this->currentRow}", $formatDate($data->start_date));
            $sheet->setCellValue("C{$this->currentRow}", $data->end_date ? $formatDate($data->end_date) : $data->toPresent);
            $sheet->setCellValue("D{$this->currentRow}", $data->position ?: 'N/A');
            $sheet->setCellValue("E{$this->currentRow}", $data->status_of_appointment ?: 'N/A');
            $sheet->setCellValue("F{$this->currentRow}", $data->monthly_salary ?: 'N/A');
            $sheet->setCellValue("G{$this->currentRow}", $data->pera ?: 'N/A');
            $sheet->setCellValue("H{$this->currentRow}", $data->department ?: 'N/A');
            $sheet->setCellValue("I{$this->currentRow}", $data->branch ?: 'N/A');
            $sheet->setCellValue("J{$this->currentRow}", $data->leave_absence_wo_pay ?: 'N/A');
            $sheet->setCellValue("K{$this->currentRow}", $data->remarks ?: 'N/A');
            $sheet->setCellValue("L{$this->currentRow}", "");

            $sheet->getStyle("B{$this->currentRow}:L{$this->currentRow}")->getAlignment()->setWrapText(true);
            $sheet->getRowDimension($this->currentRow)->setRowHeight(-1);

            $numberOfData++;

            if ($numberOfData == 13 && $index < $totalRecords - 1) {
                $this->currentRow++;
                $sheet->setCellValue("D{$this->currentRow}", "------ Continuation on next page ------");
                $sheet->mergeCells("D{$this->currentRow}:I{$this->currentRow}");
                $sheet->getStyle("D{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$this->currentRow}")->getFont()->setBold(true);
                $sheet->getStyle("D{$this->currentRow}")->getFont()->setItalic(true);
                
                $pageNumber++;
                if ($pageNumber <= count($pageStartRows)) {
                    $this->currentRow = $pageStartRows[$pageNumber - 1];
                } else {
                    // If we've exceeded predefined pages, add a new page
                    $this->currentRow = $pageStartRows[count($pageStartRows) - 1] + 55;
                }
                
                $sheet->setCellValue("D{$this->currentRow}", "------ Continuation from page " . ($pageNumber - 1) . " ------");
                $sheet->mergeCells("D{$this->currentRow}:I{$this->currentRow}");
                $sheet->getStyle("D{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$this->currentRow}")->getFont()->setBold(true);
                $sheet->getStyle("D{$this->currentRow}")->getFont()->setItalic(true);
                $this->currentRow++;
                $numberOfData = 0;

                if($pageNumber == 2){
                    $sheet->setCellValue("C67", $user->userData->surname);
                    $sheet->setCellValue("E67", $user->userData->first_name);
                    $sheet->setCellValue("H67", $user->userData->middle_name);
                    $sheet->setCellValue("J67", "");
                    $sheet->setCellValue("C70", Carbon::parse($user->userData->date_of_birth)->format('F d, Y'));
                    $sheet->setCellValue("F70", $user->userData->place_of_birth);
                    $sheet->setCellValue("J70", $user->userData->sex == 'No' ? 'Prefer Not To Say' : $user->userData->sex);

                    $status = '';
                    if($user->userData->civil_status == 'Single'){
                        $status = 'S';
                    }elseif($user->userData->civil_status == 'Married'){
                        $status = 'M';
                    }elseif($user->userData->civil_status == 'Widowed'){
                        $status = 'W';
                    }elseif($user->userData->civil_status == 'Separated'){
                        $status = 'S';
                    }
                    $sheet->setCellValue("K70", 'Civil Status :   '. $status);
                }elseif($pageNumber == 3){
                    $sheet->setCellValue("C122", $user->userData->surname);
                    $sheet->setCellValue("E122", $user->userData->first_name);
                    $sheet->setCellValue("H122", $user->userData->middle_name);
                    $sheet->setCellValue("J122", "");
                    $sheet->setCellValue("C125", Carbon::parse($user->userData->date_of_birth)->format('F d, Y'));
                    $sheet->setCellValue("F125", $user->userData->place_of_birth);
                    $sheet->setCellValue("J125", $user->userData->sex == 'No' ? 'Prefer Not To Say' : $user->userData->sex);

                    $status = '';
                    if($user->userData->civil_status == 'Single'){
                        $status = 'S';
                    }elseif($user->userData->civil_status == 'Married'){
                        $status = 'M';
                    }elseif($user->userData->civil_status == 'Widowed'){
                        $status = 'W';
                    }elseif($user->userData->civil_status == 'Separated'){
                        $status = 'S';
                    }
                    $sheet->setCellValue("K125", 'Civil Status :   '. $status);
                }elseif($pageNumber == 4){
                    $sheet->setCellValue("C177", $user->userData->surname);
                    $sheet->setCellValue("E177", $user->userData->first_name);
                    $sheet->setCellValue("H177", $user->userData->middle_name);
                    $sheet->setCellValue("J177", "");
                    $sheet->setCellValue("C180", Carbon::parse($user->userData->date_of_birth)->format('F d, Y'));
                    $sheet->setCellValue("F180", $user->userData->place_of_birth);
                    $sheet->setCellValue("J180", $user->userData->sex == 'No' ? 'Prefer Not To Say' : $user->userData->sex);

                    $status = '';
                    if($user->userData->civil_status == 'Single'){
                        $status = 'S';
                    }elseif($user->userData->civil_status == 'Married'){
                        $status = 'M';
                    }elseif($user->userData->civil_status == 'Widowed'){
                        $status = 'W';
                    }elseif($user->userData->civil_status == 'Separated'){
                        $status = 'S';
                    }
                    $sheet->setCellValue("K180", 'Civil Status :   '. $status);
                }elseif($pageNumber == 5){
                    $sheet->setCellValue("C232", $user->userData->surname);
                    $sheet->setCellValue("E232", $user->userData->first_name);
                    $sheet->setCellValue("H232", $user->userData->middle_name);
                    $sheet->setCellValue("J232", "");
                    $sheet->setCellValue("C235", Carbon::parse($user->userData->date_of_birth)->format('F d, Y'));
                    $sheet->setCellValue("F235", $user->userData->place_of_birth);
                    $sheet->setCellValue("J235", $user->userData->sex == 'No' ? 'Prefer Not To Say' : $user->userData->sex);

                    $status = '';
                    if($user->userData->civil_status == 'Single'){
                        $status = 'S';
                    }elseif($user->userData->civil_status == 'Married'){
                        $status = 'M';
                    }elseif($user->userData->civil_status == 'Widowed'){
                        $status = 'W';
                    }elseif($user->userData->civil_status == 'Separated'){
                        $status = 'S';
                    }
                    $sheet->setCellValue("K235", 'Civil Status :   '. $status);
                }
            } else {
                $this->currentRow++;
            }
        }

        if ($numberOfData < 13 || $numberOfData == $totalRecords) {
            $sheet->mergeCells("D{$this->currentRow}:I{$this->currentRow}");
            $sheet->setCellValue("D{$this->currentRow}", "** END OF TERM **");
            $sheet->getStyle("D{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$this->currentRow}")->getFont()->setBold(true);
            $sheet->getStyle("D{$this->currentRow}")->getFont()->setItalic(true);
            
            $this->currentRow++;
            $divider = "------";
            $sheet->mergeCells("D{$this->currentRow}:I{$this->currentRow}");
            $sheet->setCellValue("D{$this->currentRow}", $divider . " Nothing Follows " . $divider);
            $sheet->getStyle("D{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$this->currentRow}")->getFont()->setBold(true);
            $sheet->getStyle("D{$this->currentRow}")->getFont()->setItalic(true);
        }
    }
}