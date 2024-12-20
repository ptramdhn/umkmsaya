<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class Counter extends Component implements HasForms
{
    use InteractsWithForms;

    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $password = '';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('email')
                    ->label('email')
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
                TextInput::make('phone_number')
                    ->label('phone_number')
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
                TextInput::make('password')
                    ->label('password')
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
            ]);
    }

    public function render()
    {
        return view('livewire.counter');
    }

    public function save(): void
    {
        // dd($this->name);
        // $this->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email',
        //     'phone_number' => 'required|numeric',
        //     'password' => 'required|min:8|confirmed',
        // ]);

        dd($this->form->getState());
    }
}
