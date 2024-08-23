<?php

namespace App\Exports;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PDSExport implements WithDrawings
{
    protected $pds;

    public function __construct($pds){
        $this->pds = $pds;
    }

    public function export()
    {
        try {
            $spreadsheet = IOFactory::load(storage_path('app/templates/pds_template.xlsx'));
            foreach (['C1', 'C2', 'C3', 'C4'] as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                $this->populateSheet($sheet, $sheetName);
            }
            
            $writer = new Xlsx($spreadsheet);
            $filename = $this->pds['userData']->first_name . ' ' . $this->pds['userData']->surname . ' PDS.xlsx';
            
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
            $writer->save($tempFile);
            
            $fileContent = file_get_contents($tempFile);

            unlink($tempFile);
            return [
                'content' => $fileContent,
                'filename' => $filename
            ];
    
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function populateSheet($sheet, $sheetName){
        $parseDate = function($value) {
            return Carbon::parse($value)->format('m/d/Y');
        };

        $formatCurrency = function($value) {
            if($value == null){
                return '₱ 0.00';
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        switch ($sheetName) {
            case 'C1':
                $sheet->setCellValue('D10', $this->pds['userData']->surname);
                $sheet->setCellValue('D11', $this->pds['userData']->first_name);
                $sheet->setCellValue('D12', $this->pds['userData']->middle_name);
                $sheet->setCellValue('N11', $this->pds['userData']->name_extension);
                $birthDate = $parseDate($this->pds['userData']->date_of_birth);
                $sheet->setCellValue('D13', $birthDate);
                $sheet->setCellValue('D15', $this->pds['userData']->place_of_birth);

                $maleSymbol = strtolower($this->pds['userData']->sex) === 'male' ? '☑ Male' : '☐ Male';
                $femaleSymbol = strtolower($this->pds['userData']->sex) === 'female' ? '☑ Female' : '☐ Female';
                $sheet->setCellValue('D16', $maleSymbol);
                $sheet->setCellValue('E16', $femaleSymbol);

                $singleSymbol = strtolower($this->pds['userData']->civil_status) === 'single' ? '☑ Single' : '☐ Single';
                $mariedSymbol = strtolower($this->pds['userData']->civil_status) === 'maried' ? '☑ Maried' : '☐ Maried';
                $widowedSymbol = strtolower($this->pds['userData']->civil_status) === 'widowed' ? '☑ Widowed' : '☐ Widowed';
                $separatedSymbol = strtolower($this->pds['userData']->civil_status) === 'separated' ? '☑ Separated' : '☐ Separated';
                $otherSymbol = strtolower($this->pds['userData']->civil_status) === 'other' ? '☑ Other/s:' : '☐ Other/s:';
                $sheet->setCellValue('D17', $singleSymbol);
                $sheet->setCellValue('E17', $mariedSymbol);
                $sheet->setCellValue('D18', $widowedSymbol);
                $sheet->setCellValue('E18', $separatedSymbol);
                $sheet->setCellValue('D20', $otherSymbol);

                $sheet->setCellValue('D22', $this->pds['userData']->height);
                $sheet->setCellValue('D24', $this->pds['userData']->weight);
                $sheet->setCellValue('D25', $this->pds['userData']->blood_type);
                $sheet->setCellValue('D27', $this->pds['userData']->gsis);
                $sheet->setCellValue('D29', $this->pds['userData']->pagibig);
                $sheet->setCellValue('D31', $this->pds['userData']->philhealth);
                $sheet->setCellValue('D32', $this->pds['userData']->sss);
                $sheet->setCellValue('D33', $this->pds['userData']->tin);
                $sheet->setCellValue('D34', $this->pds['userData']->agency_employee_no);

                $filipinoSymbol = strtolower($this->pds['userData']->citizenship) === 'filipino' ? '☑ Filipino' : '☐ Filipino';
                $dualCitiSymbol = strtolower($this->pds['userData']->citizenship) === 'dual' ? '☑ Dual Citizenship' : '☐ Dual Citizenship';
                $byBirthSymbol = $this->pds['userData']->byBirth ? '☑ by birth' : '☐ by birth';
                $byNatSymbol = $this->pds['userData']->byNaturalization ? '☑ by naturalization' : '☐ by naturalization';
                $sheet->setCellValue('J16', $this->pds['userData']->country);

                $sheet->setCellValue('J13', $filipinoSymbol);
                $sheet->setCellValue('L13', $dualCitiSymbol);
                $sheet->setCellValue('L14', $byBirthSymbol);
                $sheet->setCellValue('M14', $byNatSymbol);

                $p_house_street = explode(',', $this->pds['userData']->p_house_street);
                $r_house_street = explode(',', $this->pds['userData']->r_house_street);

                $sheet->setCellValue('I17', $r_house_street[0]);
                $sheet->setCellValue('L17', $r_house_street[1]);
                $sheet->setCellValue('I19', $r_house_street[2]);
                $sheet->setCellValue('L19', $this->pds['userData']->residential_selectedBarangay);
                $sheet->setCellValue('I22', $this->pds['userData']->residential_selectedCity);
                $sheet->setCellValue('L22', $this->pds['userData']->residential_selectedProvince);
                $sheet->setCellValue('I24', $this->pds['userData']->residential_selectedZipcode);
                $sheet->setCellValue('I25', $p_house_street[0]);
                $sheet->setCellValue('L25', $p_house_street[1]);
                $sheet->setCellValue('I27', $p_house_street[2]);
                $sheet->setCellValue('L27', $this->pds['userData']->permanent_selectedBarangay);
                $sheet->setCellValue('I29', $this->pds['userData']->permanent_selectedCity);
                $sheet->setCellValue('L29', $this->pds['userData']->permanent_selectedProvince);
                $sheet->setCellValue('I31', $this->pds['userData']->permanent_selectedZipcode);
                $sheet->setCellValue('I32', $this->pds['userData']->tel_number);
                $sheet->setCellValue('I33', $this->pds['userData']->mobile_number);
                $sheet->setCellValue('I34', $this->pds['userData']->email);

                $sheet->setCellValue('D36', $this->pds['userSpouse']->surname);
                $sheet->setCellValue('D37', $this->pds['userSpouse']->first_name);
                $sheet->setCellValue('D38', $this->pds['userSpouse']->middle_name);
                $sheet->setCellValue('H37', $this->pds['userSpouse']->name_extension);
                $sheet->setCellValue('D39', $this->pds['userSpouse']->occupation);
                $sheet->setCellValue('D40', $this->pds['userSpouse']->employer);
                $sheet->setCellValue('D41', $this->pds['userSpouse']->business_address);
                $sheet->setCellValue('D42', $this->pds['userSpouse']->tel_number);

                $sheet->setCellValue('D43', $this->pds['userFather']->surname);
                $sheet->setCellValue('D44', $this->pds['userFather']->first_name);
                $sheet->setCellValue('D45', $this->pds['userFather']->middle_name);
                $sheet->setCellValue('H44', $this->pds['userFather']->name_extension);

                $sheet->setCellValue('D47', $this->pds['userMother']->surname);
                $sheet->setCellValue('D48', $this->pds['userMother']->first_name);
                $sheet->setCellValue('D49', $this->pds['userMother']->middle_name);

                $childRow = 37;
                foreach($this->pds['userChildren'] as $child){
                    $sheet->setCellValue("I{$childRow}", $child->childs_name);
                    $childBday = $parseDate($child->childs_birth_date);
                    $sheet->setCellValue("M{$childRow}", $childBday);
                    $childRow++;
                }

                foreach($this->pds['educBackground'] as $educ){
                    $row = 0;
                    switch($educ->level_code){
                        case 1:
                            $row = 54;
                            break;
                        case 2:
                            $row = 55;
                            break;
                        case 3:
                            $row = 56;
                            break;
                        case 4:
                            $row = 57;
                            break;
                        case 5:
                            $row = 58;
                            break;
                        default:
                            break;
                    }
                    $sheet->setCellValue("D{$row}", $educ->name_of_school);
                    $sheet->setCellValue("G{$row}", $educ->basic_educ_degree_course);
                    $sheet->setCellValue("J{$row}", $parseDate($educ->from));
                    $sheet->setCellValue("K{$row}", $parseDate($educ->to));
                    $sheet->setCellValue("L{$row}", $educ->highest_level_unit_earned);
                    $sheet->setCellValue("M{$row}", $educ->year_graduated);
                    $sheet->setCellValue("N{$row}", $educ->award);
                }

                break;
            case 'C2':
                $eligRow = 5;
                foreach($this->pds['eligibility'] as $elig){
                    $sheet->setCellValue("A{$eligRow}", $elig->eligibility);
                    $sheet->setCellValue("F{$eligRow}", $elig->rating);
                    $sheet->setCellValue("G{$eligRow}", $parseDate($elig->date));
                    $sheet->setCellValue("I{$eligRow}", $elig->place_of_exam);
                    $sheet->setCellValue("L{$eligRow}", $elig->license);
                    $sheet->setCellValue("M{$eligRow}", $parseDate($elig->date_of_validity));
                    $eligRow++;
                }

                $workExpRow = 18;
                foreach($this->pds['workExperience'] as $exp){
                    $sheet->setCellValue("A{$workExpRow}", $parseDate($exp->start_date));
                    $sheet->setCellValue("C{$workExpRow}", $parseDate($exp->end_date));
                    $sheet->setCellValue("D{$workExpRow}", $exp->position);
                    $sheet->setCellValue("G{$workExpRow}", $exp->department);
                    $sheet->setCellValue("J{$workExpRow}", $formatCurrency($exp->monthly_salary));
                    $sheet->setCellValue("K{$workExpRow}", $exp->sg_step);
                    $sheet->setCellValue("L{$workExpRow}", $exp->status_of_appointment);
                    $sheet->setCellValue("M{$workExpRow}", $exp->gov_service ? 'Y' : 'N');
                    $workExpRow++;
                }
                break;
            case 'C3':
                $volWorkRow = 6;
                foreach($this->pds['voluntaryWorks'] as $voluntary){
                    $sheet->setCellValue("A{$volWorkRow}", $voluntary->org_name . " - " . $voluntary->org_address);
                    $sheet->setCellValue("E{$volWorkRow}", $parseDate($voluntary->start_date));
                    $sheet->setCellValue("F{$volWorkRow}", $parseDate($voluntary->end_date));
                    $sheet->setCellValue("G{$volWorkRow}", $voluntary->no_of_hours);
                    $sheet->setCellValue("H{$volWorkRow}", $voluntary->position_nature);
                    $volWorkRow++;
                }

                $ldRow = 18;
                foreach($this->pds['lds'] as $ld){
                    $sheet->setCellValue("A{$ldRow}", $ld->title);
                    $sheet->setCellValue("E{$ldRow}", $parseDate($ld->start_date));
                    $sheet->setCellValue("F{$ldRow}", $parseDate($ld->end_date));
                    $sheet->setCellValue("G{$ldRow}", $ld->no_of_hours);
                    $sheet->setCellValue("H{$ldRow}", $ld->type_of_ld);
                    $sheet->setCellValue("I{$ldRow}", $ld->conducted_by);
                    $ldRow++;
                }

                $skillsHobbyRow = 42;
                $lastCellContent = "";
                foreach($this->pds['skills'] as $skill){
                    $sheet->setCellValue("A{$skillsHobbyRow}", $skill->skill);
                    $skillsHobbyRow++;
                }
                foreach($this->pds['hobbies'] as $hobby){
                    if($skillsHobbyRow != 48){
                        $sheet->setCellValue("A{$skillsHobbyRow}", $hobby->hobby);
                        $skillsHobbyRow++;
                    }else{
                        if($lastCellContent != ""){
                            $lastCellContent = $lastCellContent . ", " . $hobby->hobby;
                        }else{
                            $lastCellContent = $hobby->hobby;
                        }
                        $sheet->setCellValue("A{$skillsHobbyRow}", $lastCellContent);
                    }
                }

                $nonAcadRow = 42;
                foreach($this->pds['non_acads_distinctions'] as $nonAcad){
                    $sheet->setCellValue("C{$nonAcadRow}", $nonAcad->award . " - " . $nonAcad->ass_org_name . " - " . $parseDate($nonAcad->date_received));
                    $nonAcadRow++;
                }

                $orgMemRow = 42;
                foreach($this->pds['assOrgMemberships'] as $membership){
                    $sheet->setCellValue("I{$orgMemRow}", $membership->ass_org_name . " - " . $membership->position);
                    $orgMemRow++;
                }

                break;
            case 'C4':
                $refsRow = 52;
                foreach($this->pds['references'] as $reference){
                    $sheet->setCellValue("A{$refsRow}", $reference->firstname . " " . $reference->middle_initial . " " . $reference->surname);
                    $sheet->setCellValue("F{$refsRow}", $reference->address);
                    $number = null;
                    if($reference->tel_number){
                        $number = $reference->tel_number;
                    }else{
                        $number = $reference->mobile_number;
                    }
                    $sheet->setCellValue("G{$refsRow}", $number);
                    $refsRow++;
                }
                break;
        }
    }

    private function setCheckbox($sheet, $cellAddress, $value){
        $checkedImagePath = public_path('images/checked.png');
        $uncheckedImagePath = public_path('images/unchecked.png');
        $imagePath = (strtolower($value) === 'male') ? $checkedImagePath : $uncheckedImagePath;
        $drawing = new Drawing();
        $drawing->setName('Checkbox');
        $drawing->setDescription('Checkbox');
        $drawing->setPath($imagePath);
        $drawing->setCoordinates($cellAddress);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        $drawing->setWidth(100);
        $drawing->setHeight(100);
        $drawing->setWorksheet($sheet);
    }

    public function drawings(){
        return [];
    }

}
