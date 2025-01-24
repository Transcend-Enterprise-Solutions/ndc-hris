<?php

namespace App\Livewire\User;

use App\Models\ESignature;
use App\Models\WorkExperienceSheetTable as WES;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class WorkExperienceSheetTable extends Component
{
    public $pdfContent;
    public $sigXPos = 110;
    public $sigYPos = -50;
    public $sigSize = 100;
    public $moveResizeSig = false;
    public $addWorkExp;
    public $editWorkExp;
    public $accoms_cont;
     public $workExperiences = [];
     public $newWorkExperiences = [];


    public function render()
    {
        $this->showPDF();

        return view('livewire.user.work-experience-sheet-table');
    }

    public function toggleMoveResizeSig(){
        $this->moveResizeSig = !$this->moveResizeSig;
    }

    public function showPDF(){
        $userId = Auth::user()->id;

        $eSignature = ESignature::where('user_id', $userId)->first();
        $signatureImagePath = null;
        if ($eSignature && $eSignature->file_path) {
            $signatureImagePath = Storage::disk('public')->path($eSignature->file_path);
        }

        $myWorkExperiences = WES::where('user_id', $userId)
                ->orderBy('toPresent', 'desc')
                ->orderBy('start_date', 'desc')
                ->get();


        $pdf = PDF::loadView('pdf.wes', [
            'myWorkExperiences' => $myWorkExperiences,
            'signatureImagePath' => $signatureImagePath,
            'sigXPos' => $this->sigXPos,
            'sigYPos' => $this->sigYPos,
            'sigSize' => $this->sigSize,
        ]);

        $this->pdfContent = base64_encode($pdf->output());
    }

    public function addNewWorkExp(){
        $this->newWorkExperiences[] = [
            'start_date' => '', 
            'end_date'=> '',
            'toPresent'=> '',
            'position'=> '',
            'office_unit'=> '',
            'supervisor'=> '',
            'agency_org'=> '',
            'list_accomp_cont'=> [],
            'sum_of_duties'=> '',
        ];
    }

    public function toggleAddWorkExp(){
        $this->editWorkExp = true;
        $this->addWorkExp = true;
        $this->newWorkExperiences[] = [
            'start_date' => '', 
            'end_date'=> '',
            'toPresent'=> '',
            'position'=> '',
            'office_unit'=> '',
            'supervisor'=> '',
            'agency_org'=> '',
            'list_accomp_cont'=> [],
            'sum_of_duties'=> '',
        ];
    }

    public function removeNewWorkExp($index){
        unset($this->newWorkExperiences[$index]);
        $this->newWorkExperiences = array_values($this->newWorkExperiences);
    }

    public function addAccomplishment($index){
        $this->newWorkExperiences[$index]['list_accomp_cont'][] = $this->accoms_cont;
        $this->accoms_cont = '';
    }

    public function removeAccomplishment($index, $i){
        unset($this->newWorkExperiences[$index]['list_accomp_cont'][$i]);
        $this->newWorkExperiences = array_values($this->newWorkExperiences);
    }

    public function saveWorkExp(){
        try {
            $user = Auth::user();
            if ($user) {
                if(!$this->addWorkExp){
                    foreach ($this->workExperiences as $index => $exp) {
                        $validationRules = [
                            'workExperiences.'.$index.'.department' => 'required|string',
                            'workExperiences.'.$index.'.monthly_salary' => 'required|numeric',
                            'workExperiences.'.$index.'.start_date' => 'required|date',
                            'workExperiences.'.$index.'.gov_service' => 'required',
                            'workExperiences.'.$index.'.status_of_appointment' => 'required|string',
                        ];
                
                        if (!$exp['toPresent']) {
                            $validationRules['workExperiences.'.$index.'.end_date'] = 'required|date';
                            $exp['toPresent'] = null;
                        } else {
                            $validationRules['workExperiences.'.$index.'.toPresent'] = 'required';
                            $exp['toPresent'] = 'Present';
                            $exp['end_date'] = null;
                        }
                
                        $this->validate($validationRules);

                        $expRecord = $user->workExperience->find($exp['id']);
                        if ($expRecord) {
                            $expRecord->update([
                                'start_date' => $exp['start_date'],
                                'end_date' => $exp['end_date'] ?: null,
                                'toPresent' => $exp['toPresent'] ?: null,
                                'position' => $exp['position'],
                                'department' => $exp['department'],
                                'monthly_salary' => $exp['monthly_salary'],
                                'sg_step' => $exp['sg_step'],
                                'status_of_appointment' => $exp['status_of_appointment'],
                                'gov_service' => $exp['gov_service'],
                                'pera' => $exp['gov_service'] ? $exp['pera'] : null,
                                'branch' => $exp['gov_service'] ? $exp['branch'] : null,
                                'leave_absence_wo_pay' => $exp['gov_service'] ? $exp['leave_absence_wo_pay'] : null,
                                // 'separation_date' => $exp['separation_date'],
                                // 'separation_cause' => $exp['separation_cause'],
                                'remarks' => $exp['gov_service'] ? $exp['remarks'] : null,
                            ]);
                        }
                    }
                    $this->editWorkExp = null;
                    $this->addWorkExp = null;
                    $this->dispatch('swal', [
                        'title' => "Work Experience updated successfully!",
                        'icon' => 'success'
                    ]);
                }else{
                    foreach ($this->newWorkExperiences as $index => $exp) {
                        $validationRules = [
                            'newWorkExperiences.'.$index.'.position' => 'required',
                            'newWorkExperiences.'.$index.'.start_date' => 'required|date',
                            'newWorkExperiences.'.$index.'.office_unit' => 'required',
                            'newWorkExperiences.'.$index.'.supervisor' => 'required',
                            'newWorkExperiences.'.$index.'.agency_org' => 'required',
                            'newWorkExperiences.'.$index.'.sum_of_duties' => 'required',
                        ];
                
                        if (!$exp['toPresent']) {
                            $validationRules['newWorkExperiences.'.$index.'.end_date'] = 'required|date';
                            $exp['toPresent'] = 0;
                        } else {
                            $validationRules['newWorkExperiences.'.$index.'.toPresent'] = 'required';
                            $exp['toPresent'] = 1;
                            $exp['end_date'] = null;
                        }

                        $this->validate($validationRules);

                        WES::create([
                            'user_id' => $user->id,
                            'start_date' => $exp['start_date'],
                            'end_date' => $exp['end_date'] ?: null,
                            'toPresent' => $exp['toPresent'] ?: null,
                            'position' => $exp['position'],
                            'office_unit'=> $exp['office_unit'],
                            'supervisor'=> $exp['supervisor'],
                            'agency_org'=> $exp['agency_org'],
                            'list_accomp_cont'=> implode('|', $exp['list_accomp_cont']),
                            'sum_of_duties'=> $exp['sum_of_duties'],
                        ]);
                        
                    }

                    $this->editWorkExp = null;
                    $this->addWorkExp = null;
                    $this->newWorkExperiences = [];
                    $this->dispatch('swal', [
                        'title' => "Work Experience added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleEditWorkExp(){
        $this->editWorkExp = true;
        try{
            // $this->workExperiences = $this->pds['workExperience']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->addWorkExp = null;
        $this->editWorkExp = null;
    }
}
