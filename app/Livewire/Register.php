<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Actions\ShowPasswordAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Register extends Component implements HasForms
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
                    ->label('Name')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
                TextInput::make('phone_number')
                    ->label('Nomor Whatsapp')
                    ->helperText(str('Masukkan nomor diawali dengan **62** tanpa tanda **+**')->inlineMarkdown()->toHtmlString())
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
                TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->reactive()
                    ->columnSpan(2),
                
            ]);
    }

    public function render()
    {
        return view('livewire.register');
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

        $data = $this->form->getState();
        $user = User::create($data);
        $user->assignRole('customer');

        redirect()->route('home')
                ->with('status', 'success')
                ->with('message', 'Pendafataran Berhasil');
    }
}
