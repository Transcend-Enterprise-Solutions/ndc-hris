<?php

namespace App\Exports;

use Exception;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceRecordExport
{
    use Exportable;

    protected $filters;
    protected $currentRow = 8;

    public function __construct($filters){
        $this->filters = $filters;
    }

    public function export(){
        try {
            $spreadsheet = IOFactory::load(storage_path('app/templates/service_record.xlsx'));
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
        $sheet->setCellValue("G{$this->currentRow}", $user->userData->middle_name);
        $sheet->setCellValue("J{$this->currentRow}", "");

        $this->currentRow +=3;
        $sheet->setCellValue("C{$this->currentRow}", Carbon::parse($user->userData->date_of_birth)->format('F d, Y'));
        $sheet->setCellValue("H{$this->currentRow}", $user->userData->place_of_birth);


        $this->addDataRows($sheet, $record, $user);
    }

    protected function addDataRows($sheet, $record, $user)
    {
        $formatDate = function($value) {
            return Carbon::parse($value)->format('m/d/y');
        };

        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $this->currentRow = 24;
        $numberOfData = 0;
        $pageNumber = 1;
        $totalPages = 2;
        $totalRecords = count($record);

        foreach ($record as $index => $data) {
            $sheet->setCellValue("A{$this->currentRow}", $formatDate($data->start_date));
            $sheet->setCellValue("C{$this->currentRow}", $data->end_date ? $formatDate($data->end_date) : $data->toPresent);
            $sheet->setCellValue("E{$this->currentRow}", $data->position ?: '-do-');
            $sheet->setCellValue("F{$this->currentRow}", $data->status_of_appointment ?: '-do-');
            $sheet->setCellValue("H{$this->currentRow}", $data->monthly_salary ? $formatCurrency($data->monthly_salary): '-do-');
            $sheet->setCellValue("J{$this->currentRow}", $data->department ?: '-do-');
            $sheet->setCellValue("L{$this->currentRow}", $data->branch ?: '-do-');
            $sheet->setCellValue("M{$this->currentRow}", $data->leave_absence_wo_pay ?: '-do-');
            $sheet->setCellValue("O{$this->currentRow}", $data->remarks ?: '-do-');

            $sheet->getStyle("B{$this->currentRow}:L{$this->currentRow}")->getAlignment()->setWrapText(true);
            $sheet->getRowDimension($this->currentRow)->setRowHeight(-1);

            $numberOfData++;

            if ($numberOfData == 20 && $index < $totalRecords - 1) {
                $this->currentRow++;
                $sheet->mergeCells("A{$this->currentRow}:Q{$this->currentRow}");
                $sheet->setCellValue("A{$this->currentRow}", "");
                $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_DASHED,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                
                $this->currentRow += 2;
                $sheet->mergeCells("A{$this->currentRow}:Q{$this->currentRow}");
                $sheet->setCellValue("A{$this->currentRow}", "Page " . $pageNumber . " of " . $totalPages . " pages");
                $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                
                $sheet->setBreak("A" . ($this->currentRow + 1), Worksheet::BREAK_ROW);

                $pageNumber++;
                $this->currentRow++;
                $numberOfData = 0;
            } else {
                $this->currentRow++;
            }
        }

        if ($numberOfData < 20 || $numberOfData == $totalRecords) {
            $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_DASHED,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
            
            $this->currentRow += 2;
            $currPage = $pageNumber;
            $currRow = $this->currentRow ;
            $sheet->setCellValue("A{$currRow}", "Page " . $currPage . " of " . $totalPages . " pages");
            $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $sheet->setBreak("A" . ($this->currentRow + 1), Worksheet::BREAK_ROW);

            $this->currentRow ++;
            $sheet->getStyle("A{$this->currentRow}:N{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->mergeCells("A{$this->currentRow}:N{$this->currentRow}");
            $middleInitial = strtoupper(substr($user->userData->middle_name, 0, 1));
            $sheet->setCellValue("A{$this->currentRow}", "Service Record of " . 
                $user->userData->surname . ", " . 
                $user->userData->first_name . " " . 
                $middleInitial[0] . ".");
            
            $this->currentRow ++;
            $pageNumber ++;
            $totalPages = $pageNumber;
           
            $sheet->setCellValue("A{$this->currentRow}", "Page " . $pageNumber . " of " . $totalPages . " pages");
            $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $this->currentRow += 2;
            $sheet->setCellValue("A{$this->currentRow}", "Notes");
            $sheet->setCellValue("B{$this->currentRow}", "Services rendered with DOF were based on services record submitted to NDC dated February 18, 2003.");
            $sheet->getStyle("A{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            $this->currentRow ++;
            $sheet->setCellValue("B{$this->currentRow}", "Refunded the separation pay paid by NDC in compliance with Executive Order No. 184 dated March 10, 2003");
            $sheet->getStyle("B{$this->currentRow}")->getFont()->setBold(true);

            $this->currentRow ++;
            $sheet->setCellValue("B{$this->currentRow}", "and COA Audit Observation Memorandum No. 05-0050.");
            $sheet->getStyle("B{$this->currentRow}")->getFont()->setBold(true);
            
            $this->currentRow ++;
            $sheet->setCellValue("B{$this->currentRow}", "This supersedes service record issued from October 20, 2003 to January 2005.");
            
            $this->currentRow ++;
            $sheet->setCellValue("B{$this->currentRow}", "Leave without Pay (LWOP) is subject to COA Audit");
            
            $this->currentRow += 2;
            
            
            $this->currentRow += 2;
            $sheet->unmergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $sheet->mergeCells("A{$this->currentRow}:R{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "Issued in accordance with Executive Order No. 54, dated August 10, 1954,");
            $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $this->currentRow ++;
            $sheet->unmergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $sheet->mergeCells("A{$this->currentRow}:R{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "and in accordance with Circular No. 58, dated August 10, 1954 of the System.");
            $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $this->currentRow += 3;
            $sheet->unmergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $sheet->mergeCells("A{$this->currentRow}:R{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "CERTIFIED CORRECT:");
            $sheet->getStyle("A{$this->currentRow}:Q{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A{$this->currentRow}")->getFont()->setBold(true);

            $this->currentRow += 2;
            $sheet->unmergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $sheet->mergeCells("A{$this->currentRow}:K{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "XXXXXXXXXX");
            $sheet->mergeCells("L{$this->currentRow}:R{$this->currentRow}");
            $sheet->setCellValue("L{$this->currentRow}", "XXXXXXXXXX");
            $sheet->getStyle("A{$this->currentRow}:L{$this->currentRow}")->getFont()->setBold(true);
            $sheet->getStyle("A{$this->currentRow}:L{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            $this->currentRow ++;
            $sheet->unmergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $sheet->mergeCells("A{$this->currentRow}:K{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "Human Resource Management Officer V");
            $sheet->mergeCells("L{$this->currentRow}:R{$this->currentRow}");
            $sheet->setCellValue("L{$this->currentRow}", "Assistant General Manager");
            $sheet->getStyle("A{$this->currentRow}:L{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            $this->currentRow ++;
            $sheet->unmergeCells("O{$this->currentRow}:Q{$this->currentRow}");
            $sheet->mergeCells("A{$this->currentRow}:K{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", \Carbon\Carbon::parse(now())->format('F d, Y'));
            $sheet->mergeCells("L{$this->currentRow}:R{$this->currentRow}");
            $sheet->setCellValue("L{$this->currentRow}", "Corporate Support Group");
            $sheet->getStyle("A{$this->currentRow}:L{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        }
    }
}