<?php

namespace App\Livewire\PersonalDataSheet\Table;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PersonalDataSheetTable extends Component
{
    public function render(){
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();

        return view('livewire.personal-data-sheet.table.personal-data-sheet-table', [
            'userData' => $user->userData,
        ]);
    }
}
