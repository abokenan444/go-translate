<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Certification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('certificate_number')->disabled(),
                Forms\Components\TextInput::make('verification_code')->disabled(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(),
                Forms\Components\TextInput::make('source_language')->required(),
                Forms\Components\TextInput::make('target_language')->required(),
                Forms\Components\DatePicker::make('issue_date')->required(),
                Forms\Components\DatePicker::make('expiry_date')->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'valid' => 'Valid',
                        'revoked' => 'Revoked',
                        'expired' => 'Expired',
                    ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('certificate_number')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('source_language'),
                Tables\Columns\TextColumn::make('target_language'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'valid',
                        'danger' => 'revoked',
                        'warning' => 'expired',
                    ]),
                Tables\Columns\TextColumn::make('issue_date')->date(),
                Tables\Columns\TextColumn::make('expiry_date')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'valid' => 'Valid',
                        'revoked' => 'Revoked',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}
