<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PersonalDataSheetTable extends Component
{
    public function render(){
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();
        $educBackground = $user->employeesEducation;


        return view('livewire.user.personal-data-sheet-table', [
            'userData' => $user->userData,
            'userSpouse' => $user->employeesSpouse,
            'userMother' => $user->employeesMother,
            'userFather' => $user->employeesFather,
            'userChildren' => $user->employeesChildren,
            'educBackground' => $educBackground,
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
}
