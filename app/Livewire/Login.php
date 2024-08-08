<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $showPassword = false;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();
            if (($user->user_role === 'emp')) {
                return redirect()->intended('/home');
            }else if ($user->user_role === 'sa' ||
                $user->user_role === 'hr' ||
                $user->user_role === 'sv' ||
                $user->user_role === 'pa'
                ) 
            {
                return redirect()->intended('/dashboard');
            }
        } else {
            $this->addError('login', 'Invalid credentials.');
        }
    }

    public function render()
    {
        return view('auth.login');
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }
}

