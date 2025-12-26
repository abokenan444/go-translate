<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerLeadResource\Pages;
use App\Models\PartnerLead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerLeadResource extends Resource
{
    protected static ?string $model = PartnerLead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    
    protected static ?string $navigationGroup = 'Partner Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'office' => 'Office',
                        'freelancer' => 'Freelancer',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(20),
                    
                Forms\Components\TextInput::make('country_code')
                    ->label('Country Code')
                    ->maxLength(2),
                    
                Forms\Components\TextInput::make('city')
                    ->maxLength(100),
                    
                Forms\Components\TagsInput::make('languages')
                    ->placeholder('Add languages'),
                    
                Forms\Components\TagsInput::make('specialties')
                    ->placeholder('Add specialties'),
                    
                Forms\Components\TextInput::make('source')
                    ->maxLength(100)
                    ->helperText('How we found this lead (e.g., LinkedIn, Referral)'),
                    
                Forms\Components\Select::make('stage')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'invited' => 'Invited',
                        'onboarded' => 'Onboarded',
                        'rejected' => 'Rejected',
                    ])
                    ->default('new')
                    ->required(),
                    
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull()
                    ->rows(3),
                    
                Forms\Components\DateTimePicker::make('last_contacted_at')
                    ->label('Last Contacted'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'office' => 'info',
                        'freelancer' => 'success',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('country_code')
                    ->label('Country')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'contacted' => 'info',
                        'qualified' => 'warning',
                        'invited' => 'primary',
                        'onboarded' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('last_contacted_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'office' => 'Office',
                        'freelancer' => 'Freelancer',
                    ]),
                    
                Tables\Filters\SelectFilter::make('stage')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'invited' => 'Invited',
                        'onboarded' => 'Onboarded',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('contact')
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->action(function (PartnerLead $record) {
                        $record->update(['last_contacted_at' => now()]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerLeads::route('/'),
            'create' => Pages\CreatePartnerLead::route('/create'),
            'edit' => Pages\EditPartnerLead::route('/{record}/edit'),
        ];
    }
}
