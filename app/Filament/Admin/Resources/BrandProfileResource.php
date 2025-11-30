<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BrandProfileResource\Pages;
use App\Filament\Admin\Resources\BrandProfileResource\RelationManagers;
use App\Models\BrandProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandProfileResource extends Resource
{
    protected static ?string $model = BrandProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make("name")->required(),
                Forms\Components\TextInput::make("slug")->required(),
                Forms\Components\Select::make("user_id")->relationship("user", "name")->required(),
                Forms\Components\TextInput::make("primary_language"),
                Forms\Components\TextInput::make("primary_market"),
                Forms\Components\TagsInput::make("tone_preferences"),
                Forms\Components\TagsInput::make("forbidden_words"),
                Forms\Components\TagsInput::make("preferred_words"),
                Forms\Components\Textarea::make("style_guide"),
                Forms\Components\Toggle::make("is_active"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("user.name")->searchable(),
                Tables\Columns\TextColumn::make("primary_language"),
                Tables\Columns\TextColumn::make("primary_market"),
                Tables\Columns\IconColumn::make("is_active")->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListBrandProfiles::route('/'),
            'create' => Pages\CreateBrandProfile::route('/create'),
            'edit' => Pages\EditBrandProfile::route('/{record}/edit'),
        ];
    }
}
