<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Store;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StoreResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StoreResource\RelationManagers;
use Illuminate\Auth\Access\Gate;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->columnSpan(2)
                                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Edit') // Label tombol
                    ->modalHeading('Ubah Data') // Heading pada modal
                    ->form([
                        // Form yang akan muncul dalam modal
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->columnSpan(2)
                            ->required()
                            ->disabled(fn() => 
                                Auth()->user()->hasRole('seller')
                            ), // Disable jika sudah ada value
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->action(function (\App\Models\Store $record, array $data): void {
                        // Simpan perubahan
                        $record->update($data);
                    }),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            // 'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // $userId = Auth::id()
        if (Auth::user()->hasRole('admin')) {
            return Store::query();
        }else {
            return Store::where('user_id', Auth::id());
        }
    
    }
}
