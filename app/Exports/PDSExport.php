<?php

namespace App\Exports;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            if($value == null){
                return 'N/A';
            }
            return Carbon::parse($value)->format('m/d/Y');
        };

        $formatCurrency = function($value) {
            if($value == null){
                return 'N/A';
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $dateNow = now()->format('m/d/Y');

        switch ($sheetName) {
            case 'C1':
                $sheet->setCellValue('D10', $this->pds['userData']->surname ?? 'N/A');
                $sheet->setCellValue('D11', $this->pds['userData']->first_name ?? 'N/A');
                $sheet->setCellValue('D12', $this->pds['userData']->middle_name ?? 'N/A');
                $sheet->setCellValue('N11', $this->pds['userData']->name_extension ?? 'N/A');
                $birthDate = $parseDate($this->pds['userData']->date_of_birth ?? 'N/A');
                $sheet->setCellValue('D13', $birthDate ?? 'N/A');
                $sheet->setCellValue('D15', $this->pds['userData']->place_of_birth ?? 'N/A');

                $maleSymbol = '';
                $femaleSymbol = '';
                if(strtolower($this->pds['userData']->sex) === 'male' ||  strtolower($this->pds['userData']->sex) === 'female'){
                    $maleSymbol = strtolower($this->pds['userData']->sex) === 'male' ? '☑ Male' : '☐ Male';
                    $femaleSymbol = strtolower($this->pds['userData']->sex) === 'female' ? '☑ Female' : '☐ Female';
                }else{
                    $maleSymbol = '☐ Male';
                    $femaleSymbol = '☐ Female';
                }
                $sheet->setCellValue('D16', $maleSymbol ?? '☐ Male');
                $sheet->setCellValue('E16', $femaleSymbol ?? '☐ Female');

                $singleSymbol = strtolower($this->pds['userData']->civil_status) === 'single' ? '☑ Single' : '☐ Single';
                $mariedSymbol = strtolower($this->pds['userData']->civil_status) === 'married' ? '☑ Married' : '☐ Married';
                $widowedSymbol = strtolower($this->pds['userData']->civil_status) === 'widowed' ? '☑ Widowed' : '☐ Widowed';
                $separatedSymbol = strtolower($this->pds['userData']->civil_status) === 'separated' ? '☑ Separated' : '☐ Separated';
                $otherSymbol = strtolower($this->pds['userData']->civil_status) === 'other' ? '☑ Other/s:' : '☐ Other/s:';
                $sheet->setCellValue('D17', $singleSymbol ?? 'N/A');
                $sheet->setCellValue('E17', $mariedSymbol ?? 'N/A');
                $sheet->setCellValue('D18', $widowedSymbol ?? 'N/A');
                $sheet->setCellValue('E18', $separatedSymbol ?? 'N/A');
                $sheet->setCellValue('D20', $otherSymbol ?? 'N/A');

                $sheet->setCellValue('D22', $this->pds['userData']->height ?? 'N/A');
                $sheet->setCellValue('D24', $this->pds['userData']->weight ?? 'N/A');
                $sheet->setCellValue('D25', $this->pds['userData']->blood_type ?? 'N/A');
                $sheet->setCellValue('D27', $this->pds['userData']->gsis ?? '');
                $sheet->setCellValue('D29', $this->pds['userData']->pagibig ?? '');
                $sheet->setCellValue('D31', $this->pds['userData']->philhealth ?? '');
                $sheet->setCellValue('D32', $this->pds['userData']->sss ?? '');
                $sheet->setCellValue('D33', $this->pds['userData']->tin ?? '');
                $sheet->setCellValue('D34', $this->pds['userData']->agency_employee_no ?? '');

                $filipinoSymbol = strtolower($this->pds['userData']->citizenship) === 'filipino' ? '☑ Filipino' : '☐ Filipino';
                $dualCitiSymbol = strtolower($this->pds['userData']->citizenship) === 'dual citizenship' ? '☑ Dual Citizenship' : '☐ Dual Citizenship';
                $byBirthSymbol = strtolower($this->pds['userData']->dual_citizenship_type) === 'by birth' ? '☑ by birth' : '☐ by birth';
                $byNatSymbol = strtolower($this->pds['userData']->dual_citizenship_type) === 'by naturalization' ? '☑ by naturalization' : '☐ by naturalization';
                $sheet->setCellValue('J16', $this->pds['userData']->dual_citizenship_country ?? 'N/A');

                $sheet->setCellValue('J13', $filipinoSymbol ?? '☐ Filipino');
                $sheet->setCellValue('L13', $dualCitiSymbol ?? '☐ Dual Citizenship');
                $sheet->setCellValue('L14', $byBirthSymbol ?? '☐ by birth');
                $sheet->setCellValue('M14', $byNatSymbol ?? '☐ by naturalization');

                $p_house_street = explode(',', $this->pds['userData']->p_house_street ?? 'N/A');
                $r_house_street = explode(',', $this->pds['userData']->r_house_street ?? 'N/A');

                $sheet->setCellValue('I17', $r_house_street[0] ?? 'N/A');
                $sheet->setCellValue('L17', $r_house_street[1] ?? 'N/A');
                $sheet->setCellValue('I19', $r_house_street[2] ?? 'N/A');
                $sheet->setCellValue('L19', $this->pds['userData']->residential_selectedBarangay ?? 'N/A');
                $sheet->setCellValue('I22', $this->pds['userData']->residential_selectedCity ?? 'N/A');
                $sheet->setCellValue('L22', $this->pds['userData']->residential_selectedProvince ?? 'N/A');
                $sheet->setCellValue('I24', $this->pds['userData']->residential_selectedZipcode ?? 'N/A');
                $sheet->setCellValue('I25', $p_house_street[0] ?? 'N/A');
                $sheet->setCellValue('L25', $p_house_street[1] ?? 'N/A');
                $sheet->setCellValue('I27', $p_house_street[2] ?? 'N/A');
                $sheet->setCellValue('L27', $this->pds['userData']->permanent_selectedBarangay ?? 'N/A');
                $sheet->setCellValue('I29', $this->pds['userData']->permanent_selectedCity ?? 'N/A');
                $sheet->setCellValue('L29', $this->pds['userData']->permanent_selectedProvince ?? 'N/A');
                $sheet->setCellValue('I31', $this->pds['userData']->permanent_selectedZipcode ?? 'N/A');
                $sheet->setCellValue('I32', $this->pds['userData']->tel_number ?? 'N/A');
                $sheet->setCellValue('I33', $this->pds['userData']->mobile_number ?? 'N/A');
                $sheet->setCellValue('I34', $this->pds['userData']->email ?? 'N/A');

                $sheet->setCellValue('D36', $this->pds['userSpouse']->surname ?? 'N/A');
                $sheet->setCellValue('D37', $this->pds['userSpouse']->first_name ?? 'N/A');
                $sheet->setCellValue('D38', $this->pds['userSpouse']->middle_name ?? 'N/A');
                $sheet->setCellValue('H37', $this->pds['userSpouse']->name_extension ?? 'N/A');
                $sheet->setCellValue('D39', $this->pds['userSpouse']->occupation ?? 'N/A');
                $sheet->setCellValue('D40', $this->pds['userSpouse']->employer ?? 'N/A');
                $sheet->setCellValue('D41', $this->pds['userSpouse']->business_address ?? 'N/A');
                $sheet->setCellValue('D42', $this->pds['userSpouse']->tel_number ?? 'N/A');

                $sheet->setCellValue('D43', $this->pds['userFather']->surname ?? 'N/A');
                $sheet->setCellValue('D44', $this->pds['userFather']->first_name ?? 'N/A');
                $sheet->setCellValue('D45', $this->pds['userFather']->middle_name ?? 'N/A');
                $sheet->setCellValue('H44', $this->pds['userFather']->name_extension ?? 'N/A');

                $sheet->setCellValue('D47', $this->pds['userMother']->surname ?? 'N/A');
                $sheet->setCellValue('D48', $this->pds['userMother']->first_name ?? 'N/A');
                $sheet->setCellValue('D49', $this->pds['userMother']->middle_name ?? 'N/A');

                $childRow = 37;
                if(!$this->pds['userChildren']->isEmpty()){
                    foreach($this->pds['userChildren'] as $child){
                        $sheet->setCellValue("I{$childRow}", $child->childs_name ?? '');
                        $childBday = $parseDate($child->childs_birth_date);
                        $sheet->setCellValue("M{$childRow}", $childBday ?? 'N/A');
                        $childRow++;
                    }
                }else{
                    for($i = 37; $i <= 48; $i++){
                        $sheet->setCellValue("I{$i}", 'N/A');
                        $sheet->setCellValue("M{$i}", 'N/A');
                    }
                }

                if(!$this->pds['educBackground']->isEmpty()){
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
                        $sheet->setCellValue("D{$row}", $educ->name_of_school ?? 'N/A');
                        $sheet->setCellValue("G{$row}", $educ->basic_educ_degree_course ?? 'N/A');
                        $sheet->setCellValue("J{$row}", $parseDate($educ->from) ?? 'N/A');
                        $sheet->setCellValue("K{$row}", $parseDate($educ->to) ?? 'N/A');
                        $sheet->setCellValue("L{$row}", $educ->highest_level_unit_earned ?? 'N/A');
                        $sheet->setCellValue("M{$row}", $educ->year_graduated ?? 'N/A');
                        $sheet->setCellValue("N{$row}", $educ->award ?? 'N/A');
                    }
                }else{
                    for($i = 54; $i <= 58; $i++){
                        $sheet->setCellValue("D{$i}", 'N/A');
                        $sheet->setCellValue("G{$i}", 'N/A');
                        $sheet->setCellValue("J{$i}", 'N/A');
                        $sheet->setCellValue("K{$i}", 'N/A');
                        $sheet->setCellValue("L{$i}", 'N/A');
                        $sheet->setCellValue("M{$i}", 'N/A');
                        $sheet->setCellValue("N{$i}", 'N/A');
                    }
                }

                $sheet->setCellValue('L60', $dateNow);

                break;
            case 'C2':
                $eligRow = 5;
                if($this->pds['eligibility']){
                    foreach($this->pds['eligibility'] as $elig){
                        $sheet->setCellValue("A{$eligRow}", $elig->eligibility ?? '');
                        $sheet->setCellValue("F{$eligRow}", $elig->rating ?? 'N/A');
                        $sheet->setCellValue("G{$eligRow}", $parseDate($elig->date));
                        $sheet->setCellValue("I{$eligRow}", $elig->place_of_exam ?? 'N/A');
                        $sheet->setCellValue("L{$eligRow}", $elig->license ?? 'N/A');
                        $sheet->setCellValue("M{$eligRow}", $parseDate($elig->date_of_validity) ?? 'N/A');
                        $eligRow++;
                    }
                }

                $workExpRow = 18;
                if($this->pds['workExperience']){
                    foreach($this->pds['workExperience'] as $exp){
                        $sheet->setCellValue("A{$workExpRow}", $parseDate($exp->start_date));
                        $sheet->setCellValue("C{$workExpRow}", $parseDate($exp->end_date));
                        $sheet->setCellValue("D{$workExpRow}", $exp->position ?? 'N/A');
                        $sheet->setCellValue("G{$workExpRow}", $exp->department ?? 'N/A');
                        $sheet->setCellValue("J{$workExpRow}", $formatCurrency($exp->monthly_salary));
                        $sheet->setCellValue("K{$workExpRow}", $exp->sg_step ?? 'N/A');
                        $sheet->setCellValue("L{$workExpRow}", $exp->status_of_appointment ?? 'N/A');
                        $sheet->setCellValue("M{$workExpRow}", $exp->gov_service ? 'Y' : 'N');
                        $workExpRow++;
                    }
                }

                $sheet->setCellValue('K47', $dateNow);

                break;
            case 'C3':
                $volWorkRow = 6;
                if($this->pds['voluntaryWorks']){
                    foreach($this->pds['voluntaryWorks'] as $voluntary){
                        $sheet->setCellValue("A{$volWorkRow}", $voluntary->org_name ?? '' . " - " . $voluntary->org_address ?? '');
                        $sheet->setCellValue("E{$volWorkRow}", $parseDate($voluntary->start_date));
                        $sheet->setCellValue("F{$volWorkRow}", $parseDate($voluntary->end_date));
                        $sheet->setCellValue("G{$volWorkRow}", $voluntary->no_of_hours ?? 'N/A');
                        $sheet->setCellValue("H{$volWorkRow}", $voluntary->position_nature ?? 'N/A');
                        $volWorkRow++;
                    }
                }

                $ldRow = 18;
                if($this->pds['lds']){
                    foreach($this->pds['lds'] as $ld){
                        $sheet->setCellValue("A{$ldRow}", $ld->title ?? '');
                        $sheet->setCellValue("E{$ldRow}", $parseDate($ld->start_date));
                        $sheet->setCellValue("F{$ldRow}", $parseDate($ld->end_date));
                        $sheet->setCellValue("G{$ldRow}", $ld->no_of_hours ?? 'N/A');
                        $sheet->setCellValue("H{$ldRow}", $ld->type_of_ld ?? 'N/A');
                        $sheet->setCellValue("I{$ldRow}", $ld->conducted_by ?? 'N/A');
                        $ldRow++;
                    }
                }

                $skillsHobbyRow = 42;
                $lastCellContent = "";
                if($this->pds['skills']){
                    foreach($this->pds['skills'] as $skill){
                        $sheet->setCellValue("A{$skillsHobbyRow}", $skill->skill ?? '');
                        $skillsHobbyRow++;
                    }
                }

                if($this->pds['hobbies']){
                    foreach($this->pds['hobbies'] as $hobby){
                        if($skillsHobbyRow != 48){
                            $sheet->setCellValue("A{$skillsHobbyRow}", $hobby->hobby ?? '');
                            $skillsHobbyRow++;
                        }else{
                            if($lastCellContent != ""){
                                $lastCellContent = $lastCellContent . ", " . $hobby->hobby ?? '';
                            }else{
                                $lastCellContent = $hobby->hobby ?? '';
                            }
                            $sheet->setCellValue("A{$skillsHobbyRow}", $lastCellContent);
                        }
                    }
                }

                $nonAcadRow = 42;
                if($this->pds['non_acads_distinctions']){
                    foreach($this->pds['non_acads_distinctions'] as $nonAcad){
                        $sheet->setCellValue("C{$nonAcadRow}", $nonAcad->award ?? '' . " - " . $nonAcad->ass_org_name ?? '' . " - " . $parseDate($nonAcad->date_received));
                        $nonAcadRow++;
                    }
                }

                $orgMemRow = 42;
                if($this->pds['assOrgMemberships']){
                    foreach($this->pds['assOrgMemberships'] as $membership){
                        $sheet->setCellValue("I{$orgMemRow}", $membership->ass_org_name ?? '' . " - " . $membership->position ?? '');
                        $orgMemRow++;
                    }
                }

                $sheet->setCellValue('I50', $dateNow);

                break;
            case 'C4':
                $refsRow = 52;
                if(!$this->pds['references']->isEmpty()){
                    foreach($this->pds['references'] as $reference){
                        $sheet->setCellValue("A{$refsRow}", $reference->firstname ?? '' . " " . $reference->middle_initial ?? '' . " " . $reference->surname ?? '');
                        $sheet->setCellValue("F{$refsRow}", $reference->address ?? 'N/A');
                        $number = null;
                        if($reference->tel_number){
                            $number = $reference->tel_number;
                        }elseif($reference->mobile_number){
                            $number = $reference->mobile_number;
                        }else{
                            $number = 'N/A';
                        }
                        $sheet->setCellValue("G{$refsRow}", $number);
                        $refsRow++;
                    }
                }else{
                    for($i = 52; $i <= 54; $i++){
                        $sheet->setCellValue("A{$i}", 'N/A');
                        $sheet->setCellValue("F{$i}", 'N/A');
                        $sheet->setCellValue("G{$i}", 'N/A');
                    }
                }

                if($this->pds['pds_c4_answers']){
                    $sheet->setCellValue("G6", '☐ YES');
                    $sheet->setCellValue("J6", '☐ NO');
                    $sheet->setCellValue("G8", '☐ YES');
                    $sheet->setCellValue("J8", '☐ NO');
                    $sheet->setCellValue("H11", 'N/A');
                    $sheet->setCellValue("G13", '☐ YES');
                    $sheet->setCellValue("J13", '☐ NO');
                    $sheet->setCellValue("H15", 'N/A');
                    $sheet->setCellValue("G18", '☐ YES');
                    $sheet->setCellValue("J18", '☐ NO');
                    $sheet->setCellValue("K20", 'N/A');
                    $sheet->setCellValue("K21", 'N/A');
                    $sheet->setCellValue("G13", '☐ YES');
                    $sheet->setCellValue("J13", '☐ NO');
                    $sheet->setCellValue("H15", 'N/A');
                    $sheet->setCellValue("G18", '☐ YES');
                    $sheet->setCellValue("J18", '☐ NO');
                    $sheet->setCellValue("K20", 'N/A');
                    $sheet->setCellValue("K21", 'N/A');
                    $sheet->setCellValue("G23", '☐ YES');
                    $sheet->setCellValue("J23", '☐ NO');
                    $sheet->setCellValue("H25", 'N/A');
                    $sheet->setCellValue("J27", '☐ NO');
                    $sheet->setCellValue("H29", 'N/A');
                    $sheet->setCellValue("G31", '☐ YES');
                    $sheet->setCellValue("J31", '☐ NO');
                    $sheet->setCellValue("K32", 'N/A');
                    $sheet->setCellValue("G34", '☐ YES');
                    $sheet->setCellValue("J34", '☐ NO');
                    $sheet->setCellValue("K35", 'N/A');
                    $sheet->setCellValue("G37", '☐ YES');
                    $sheet->setCellValue("J37", '☐ NO');
                    $sheet->setCellValue("H39", 'N/A');
                    $sheet->setCellValue("G43", '☐ YES');
                    $sheet->setCellValue("J43", '☐ NO');
                    $sheet->setCellValue("L44", 'N/A');
                    $sheet->setCellValue("G45", '☐ YES');
                    $sheet->setCellValue("J45", '☐ NO');
                    $sheet->setCellValue("L46", 'N/A');
                    $sheet->setCellValue("G47", '☐ YES');
                    $sheet->setCellValue("J47", '☐ NO');
                    $sheet->setCellValue("L48", 'N/A');
                    foreach($this->pds['pds_c4_answers'] as $ans){
                        switch($ans->question_number){
                            case 34:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G6", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J6", $aNo ?? '☐ NO');
                                }elseif($ans->question_letter == "b"){
                                    $bYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $bNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G8", $bYes ?? '☐ YES');
                                    $sheet->setCellValue("J8", $bNo ?? '☐ NO');
                                    $sheet->setCellValue("H11", $ans->details ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G6", '☐ YES');
                                    $sheet->setCellValue("J6", '☐ NO');
                                    $sheet->setCellValue("G8", '☐ YES');
                                    $sheet->setCellValue("J8", '☐ NO');
                                    $sheet->setCellValue("H11", 'N/A');
                                }
                                break;
                            case 35:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G13", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J13", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("H15", $ans->details ?? 'N/A');
                                }elseif($ans->question_letter == "b"){
                                    $bYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $bNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G18", $bYes ?? '☐ YES');
                                    $sheet->setCellValue("J18", $bNo ?? '☐ NO');
                                    $sheet->setCellValue("K20", $ans->date_filed ? Carbon::parse($ans->date_filed)->format('m/d/Y') : 'N/A');
                                    $sheet->setCellValue("K21", $ans->status ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G13", '☐ YES');
                                    $sheet->setCellValue("J13", '☐ NO');
                                    $sheet->setCellValue("H15", 'N/A');
                                    $sheet->setCellValue("G18", '☐ YES');
                                    $sheet->setCellValue("J18", '☐ NO');
                                    $sheet->setCellValue("K20", 'N/A');
                                    $sheet->setCellValue("K21", 'N/A');
                                }
                                break;
                            case 36:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G23", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J23", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("H25", $ans->details ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G23", '☐ YES');
                                    $sheet->setCellValue("J23", '☐ NO');
                                    $sheet->setCellValue("H25", 'N/A');
                                }
                                break;
                            case 37:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G27", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J27", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("H29", $ans->details ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G27", '☐ YES');
                                    $sheet->setCellValue("J27", '☐ NO');
                                    $sheet->setCellValue("H29", 'N/A');
                                }
                                break;
                            case 38:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G31", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J31", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("K32", $ans->details ?? 'N/A');
                                }elseif($ans->question_letter == "b"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G34", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J34", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("K35", $ans->details ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G31", '☐ YES');
                                    $sheet->setCellValue("J31", '☐ NO');
                                    $sheet->setCellValue("K32", 'N/A');
                                    $sheet->setCellValue("G34", '☐ YES');
                                    $sheet->setCellValue("J34", '☐ NO');
                                    $sheet->setCellValue("K35", 'N/A');
                                }
                                break;
                            case 39:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G37", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J37", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("H39", $ans->details ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G37", '☐ YES');
                                    $sheet->setCellValue("J37", '☐ NO');
                                    $sheet->setCellValue("H39", 'N/A');
                                }
                                break;
                            case 40:
                                if($ans->question_letter == "a"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G43", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J43", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("L44", $ans->details ?? 'N/A');
                                }elseif($ans->question_letter == "b"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G45", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J45", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("L46", $ans->details ?? 'N/A');
                                }elseif($ans->question_letter == "c"){
                                    $aYes = $ans && $ans->answer ? '☑ YES' : '☐ YES';
                                    $aNo = $ans && !$ans->answer ? '☑ NO' : '☐ NO';
                                    $sheet->setCellValue("G47", $aYes ?? '☐ YES');
                                    $sheet->setCellValue("J47", $aNo ?? '☐ NO');
                                    $sheet->setCellValue("L48", $ans->details ?? 'N/A');
                                }else{
                                    $sheet->setCellValue("G43", '☐ YES');
                                    $sheet->setCellValue("J43", '☐ NO');
                                    $sheet->setCellValue("L44", 'N/A');
                                    $sheet->setCellValue("G45", '☐ YES');
                                    $sheet->setCellValue("J45", '☐ NO');
                                    $sheet->setCellValue("L46", 'N/A');
                                    $sheet->setCellValue("G47", '☐ YES');
                                    $sheet->setCellValue("J47", '☐ NO');
                                    $sheet->setCellValue("L48", 'N/A');
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }

                if($this->pds['pds_gov_id']){
                    $sheet->setCellValue("D61", $this->pds['pds_gov_id']->gov_id ?? 'N/A');
                    $sheet->setCellValue("D62", $this->pds['pds_gov_id']->id_number ?? 'N/A');
                    $sheet->setCellValue("D64", $this->pds['pds_gov_id']->date_of_issuance ? Carbon::parse($this->pds['pds_gov_id']->date_of_issuance)->format('m/d/Y') : 'N/A');
                }else{
                    $sheet->setCellValue("D61", 'N/A');
                    $sheet->setCellValue("D62", 'N/A');
                    $sheet->setCellValue("D64", 'N/A');
                }

                $sheet->setCellValue('F64', $dateNow);

                break;
        }
    }

}
