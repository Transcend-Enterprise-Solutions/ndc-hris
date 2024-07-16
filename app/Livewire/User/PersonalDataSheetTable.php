<?php

namespace App\Livewire\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonalDataSheetTable extends Component
{
    public $pds;

    public function render(){
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();

        $this->pds = [
            'userData' => $user->userData,
            'userSpouse' => $user->employeesSpouse,
            'userMother' => $user->employeesMother,
            'userFather' => $user->employeesFather,
            'userChildren' => $user->employeesChildren,
            'educBackground' => $user->employeesEducation,
            'eligibility' => $user->eligibility,
            'workExperience' => $user->workExperience,
            'voluntaryWorks' => $user->voluntaryWorks,
            'lds' => $user->learningAndDevelopment,
            'skills' => $user->skills,
            'hobbies' => $user->hobbies,
            'non_acads_distinctions' => $user->nonAcadDistinctions,
            'assOrgMemberships' => $user->assOrgMembership,
            'references' => $user->charReferences,
        ];

        return view('livewire.user.personal-data-sheet-table', [
            'userData' => $user->userData,
            'userSpouse' => $user->employeesSpouse,
            'userMother' => $user->employeesMother,
            'userFather' => $user->employeesFather,
            'userChildren' => $user->employeesChildren,
            'educBackground' => $user->employeesEducation,
            'eligibility' => $user->eligibility,
            'workExperience' => $user->workExperience,
            'voluntaryWorks' => $user->voluntaryWorks,
            'lds' => $user->learningAndDevelopment,
            'skills' => $user->skills,
            'hobbies' => $user->hobbies,
            'non_acads_distinctions' => $user->nonAcadDistinctions,
            'assOrgMemberships' => $user->assOrgMembership,
            'references' => $user->charReferences,
        ]);
    }

    public function exportPDS(){
        try{
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
