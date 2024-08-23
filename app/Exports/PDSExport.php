<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;

class PDSExport 
{
    protected $pds;

    public function __construct($pds){
        $this->pds = $pds;
    }
    public function export(){
        try {
            $spreadsheet = IOFactory::load(storage_path('app/templates/pds_template.xlsx'));
            foreach (['C1', 'C2', 'C3', 'C4'] as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                $this->populateSheet($sheet, $sheetName);
            }
            
            $writer = IOFactory::createWriter($spreadsheet, 'Html');
            $html = '';
            ob_start();
            $writer->save('php://output');
            $html = ob_get_clean();
            
            $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir()]);
            $mpdf->WriteHTML($html);
            
            $filename = $this->pds['userData']->first_name . ' ' . $this->pds['userData']->surname . ' PDS.pdf';
    
            return [
                'content' => $mpdf->Output('', 'S'),
                'filename' => $filename
            ];
    
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    private function populateSheet($sheet, $sheetName){
        switch ($sheetName) {
            case 'C1':
                $sheet->setCellValue('D10', $this->pds['userData']->first_name);
                // ... populate other cells for C1
                break;
            case 'C2':
                // ... populate cells for C2
                break;
            case 'C3':
                // ... populate cells for C3
                break;
            case 'C4':
                // ... populate cells for C4
                break;
        }
    }
    
}
