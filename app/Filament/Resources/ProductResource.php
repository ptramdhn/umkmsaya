<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Store;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('product')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Select::make('store_id')
                    ->relationship('store', 'name', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->default(
                        Auth::user()->store?->id
                    )
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required(),
                Forms\Components\TextInput::make('stock')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('store.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('stock')->visible(!Auth::user()->hasRole('customer')),
                Tables\Columns\TextColumn::make('price'),
                
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
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('product')
                            ->required()
                            ->columnSpan(2),
                            Forms\Components\Select::make('store_id')
                            ->relationship('store', 'name', function ($query) {
                                $query->where('user_id', Auth::id());
                            })
                            ->default(
                                Auth::user()->store?->id
                            )
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->action(function (\App\Models\Product $record, array $data): void {
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            // 'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // $userId = Auth::id()
        $user = Auth::user();
        // $userId = $user->id;

        if ($user->hasRole('admin')) {
            return Product::query();
        }elseif ($user->hasRole('seller')) {
            $storeId = Auth::user()->store->id;

            return Product::where('store_id', $storeId);
        }else {
            return Product::whereHas('transactions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }
    
    }
}
