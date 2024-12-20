<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Cart as ModelsCart;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class Cart extends Component implements HasForms
{
    use InteractsWithForms;

    public $user_id;

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
                TextInput::make('user_id')
                    ->label('User ID')
                    ->columnSpan(2)
                    ->default($this->user_id)
                    ->required(),
                TextInput::make('product_id')
                    ->label('Product ID')
                    ->columnSpan(2)
                    ->required(),
            ]);
    }

    public function render()
    {
        return view('livewire.cart');
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
        $cart = ModelsCart::create($data);

        redirect()->route('home')
                ->with('status', 'success')
                ->with('message', 'Pendafataran Berhasil');
    }
}
