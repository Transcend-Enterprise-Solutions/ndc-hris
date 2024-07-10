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
            if ($user->user_role === 'sa') {
                session(['user_role' => $user->user_role]);
                return redirect()->intended('/dashboard');
            }
            elseif (($user->user_role === 'emp')) {
                session(['user_role' => $user->user_role]);
                return redirect()->intended('/home');
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

