<?php

namespace App\Livewire;

use id;
use App\Models\User;
use App\Models\Store;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class DaftarToko extends Component implements HasForms
{
    use InteractsWithForms;

    public $name = '';
    public $description = '';
    public $user_id = '';

    public function mount()
    {
        // Pastikan user login dan mendapatkan ID user
        if (auth()->check()) {
            $this->user_id = auth()->id();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->label('User ID')
                    ->columnSpan(2)
                    ->default($this->user_id)
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Toko')
                    ->required()
                    ->columnSpan(2),
                TextInput::make('description')
                    ->label('Deskripsi Toko')
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public function render()
    {
        return view('livewire.daftar-toko');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = User::find($data['user_id']);
        // dd($data);
        Store::create($data);
        $user->assignRole('seller');

        redirect()->route('home')
                ->with('status', 'success')
                ->with('message', 'Pendafataran Toko Berhasil');
    }
}
