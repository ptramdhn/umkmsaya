<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;

class TransactionResource extends Resource
{
    // protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Transaksi'),
                Forms\Components\Select::make('product_id')
                    ->label('Nama Produk')
                    ->relationship('product', 'name') // Relasi ke model Product dan ambil kolom 'name'
                    ->default(fn ($record) => $record->product_id ?? null),
                Forms\Components\TextInput::make('payment_status')
                    ->label('Status Pembayaran'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah Pesanan'),
                Forms\Components\TextInput::make('total_amount')
                    ->label('Total Harga'),
                Forms\Components\Select::make('user_id')
                    ->label('Nama Pembeli')
                    ->relationship('user', 'name')
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('store.name'),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('total_amount'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // ViewAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            // 'create' => Pages\CreateTransaction::route('/create'),
            // 'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // $userId = Auth::id()
        $user = Auth::user();
        // $userId = $user->id;

        if ($user->hasRole('admin')) {
            return Transaction::query();
        }elseif ($user->hasRole('seller')) {
            $storeId = Auth::user()->store->id;

            return Transaction::where('store_id', $storeId)
                                ->orderBy('created_at', 'desc'); // Mengurutkan berdasarkan created_at secara descending (terbaru)
        }else {
            return Transaction::where('user_id', $user->id);
        } 
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
