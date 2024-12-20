<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\PasswordInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Auth;

class Masuk extends Component implements HasForms
{
    use InteractsWithForms;

    public $email = '';
    public $password = '';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
            ]);
    }

    public function render()
    {
        return view('livewire.masuk');
    }

    public function login()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->route('home')
                    ->with('status', 'success')
                    ->with('message', 'Selamat Datang Di UMKMku');
        } else {
            redirect()->route('masuk')
                ->with('status', 'error')
                ->with('message', 'Email/Password Salah!');
        }
        
    }
    
    public function loginStore()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $slug = request()->route('masukStore');
            return redirect()->route('store.show', $slug)
                    ->with('status', 'success')
                    ->with('message', 'Selamat Datang Di UMKMku');
        } else {
            redirect()->route('masuk')
                ->with('status', 'error')
                ->with('message', 'Email/Password Salah!');
        }
        
    }
}
