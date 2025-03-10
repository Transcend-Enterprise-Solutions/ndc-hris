<?php

namespace App\Livewire\Admin;

use App\Mail\RegistrationNotification;
use App\Models\RegistrationOtp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;

class EmployeeRegistrationTable extends Component
{
    use WithPagination;

    public $search;
    public $genOtp;
    public $email;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function render()
    {
        $registrations = RegistrationOtp::when($this->search, function ($query) {
                            return $query->search(trim($this->search));
                        })
                        ->paginate($this->pageSize);

        foreach($registrations as $reg){
            $reg->admin = User::where('id', $reg->provided_by)->first()->name;
            $reg->user = $reg->user_id ? User::where('id', $reg->user_id)->first()->name : '';
        }

        return view('livewire.admin.employee-registration-table' , [
            'registrations' => $registrations,
        ]);
    }

    public function toggleAddRegOtp(){
        $this->genOtp = true;
    }

    public function submitRegOtp(){
        $admin = Auth::user();
        $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        try{
            $this->validate([
                'email' => 'required:email',
            ]);

            $mailed = Mail::to($this->email)->send(new RegistrationNotification($admin->email, $otp));

            if($mailed){
                RegistrationOtp::create([
                    'otp' => $otp,
                    'email' => $this->email,
                    'status' => 0,
                    'provided_by' => $admin->id,
                    'date_provided' => now(),
                ]);

                $this->dispatch('swal', [
                    'title' => 'Registration OTP mailed successfully',
                    'icon' => 'success'
                ]);
                $this->resetVariables();
            }else{
                $this->dispatch('swal', [
                    'title' => 'Unexpected error. Please try again later',
                    'icon' => 'error'
                ]);
                $this->resetVariables();
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->email = null;
        $this->genOtp = null;
    }
}
