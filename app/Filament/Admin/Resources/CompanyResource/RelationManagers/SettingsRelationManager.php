<?php
namespace App\Filament\Admin\Resources\CompanyResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;

class SettingsRelationManager extends RelationManager
{
    protected static string $relationship = 'setting';
    protected static ?string $title = 'الإعدادات';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            Forms\Components\KeyValue::make('enabled_features')->label('الميزات المفعلة')->helperText('مثال: glossary, brand_voice, cultural_memory'),
            Forms\Components\KeyValue::make('allowed_models')->label('النماذج المسموحة')->helperText('مثال: gpt-4, gpt-4o, gpt-3.5'),
            Forms\Components\TextInput::make('rate_limit_per_minute')->numeric()->label('الحد/دقيقة')->default(120),
            Forms\Components\TextInput::make('max_tokens_monthly')->numeric()->label('أقصى التوكنات شهرياً')->default(0),
        ]);
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('ID'),
            Tables\Columns\TextColumn::make('rate_limit_per_minute')->label('الحد/دقيقة'),
            Tables\Columns\TextColumn::make('max_tokens_monthly')->label('أقصى توكنات'),
        ])->headerActions([
            Tables\Actions\CreateAction::make(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
}
