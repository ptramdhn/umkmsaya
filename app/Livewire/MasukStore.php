<?php

namespace App\Livewire;

use layout;
use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\PasswordInput;
use Filament\Forms\Concerns\InteractsWithForms;

class MasukStore extends Component implements HasForms
{
    use InteractsWithForms;

    public $email = '';
    public $password = '';
    public $slug;

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

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.masukStore', [
            'storeSlug' => $this->slug,
        ])
        ->layout('components.store', [
            // Data yang diteruskan ke layout
            'storeSlug' => $this->slug,
        ]);
    }
    
    public function loginStore()
    {
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            
            if (!$this->slug) {
                return redirect()->route('masuk')
                    ->with('status', 'error')
                    ->with('message', 'Slug toko tidak ditemukan.');
            }

            return redirect()->route('store.show', $this->slug)
                    ->with('status', 'success')
                    ->with('message', 'Selamat Datang Di UMKMku');
        } else {
            redirect()->route('masuk')
                ->with('status', 'error')
                ->with('message', 'Email/Password Salah!');
        }
        
    }
}
