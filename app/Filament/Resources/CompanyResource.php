<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationGroup = 'Partners & Companies';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationLabel = 'Companies';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Company Name'),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),
                        
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://'),
                        
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Primary Contact'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Business Details')
                    ->schema([
                        Forms\Components\TextInput::make('business_type')
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('industry')
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('size')
                            ->maxLength(50)
                            ->label('Company Size'),
                        
                        Forms\Components\TextInput::make('tax_id')
                            ->maxLength(100)
                            ->label('Tax ID / Registration Number'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->maxLength(500),
                        
                        Forms\Components\TextInput::make('city')
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('country')
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('postal_code')
                            ->maxLength(20),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Logo & Settings')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->image()
                            ->directory('company-logos')
                            ->maxSize(2048)
                            ->label('Company Logo'),
                        
                        Forms\Components\KeyValue::make('settings')
                            ->label('Additional Settings')
                            ->keyLabel('Setting Name')
                            ->valueLabel('Value'),
                    ]),
                
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                        
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->label('Verification Date'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(1000),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-company.png')),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'suspended',
                    ])
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ]),
                
                Tables\Filters\Filter::make('verified')
                    ->query(fn ($query) => $query->whereNotNull('verified_at'))
                    ->label('Verified Only'),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Company $record) => $record->update(['verified_at' => now()]))
                    ->visible(fn (Company $record) => !$record->verified_at),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify')
                        ->label('Verify Selected')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['verified_at' => now()])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Company Overview')
                    ->schema([
                        Components\ImageEntry::make('logo_path')
                            ->label('Logo')
                            ->height(100),
                        Components\TextEntry::make('name'),
                        Components\TextEntry::make('email')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),
                        Components\TextEntry::make('phone')
                            ->icon('heroicon-m-phone'),
                        Components\TextEntry::make('website')
                            ->url(fn ($record) => $record->website)
                            ->openUrlInNewTab(),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'warning',
                                'suspended' => 'danger',
                            }),
                    ])
                    ->columns(2),
                
                Components\Section::make('Business Information')
                    ->schema([
                        Components\TextEntry::make('business_type'),
                        Components\TextEntry::make('industry'),
                        Components\TextEntry::make('size'),
                        Components\TextEntry::make('tax_id'),
                    ])
                    ->columns(2),
                
                Components\Section::make('Location')
                    ->schema([
                        Components\TextEntry::make('address'),
                        Components\TextEntry::make('city'),
                        Components\TextEntry::make('country'),
                        Components\TextEntry::make('postal_code'),
                    ])
                    ->columns(2),
                
                Components\Section::make('Additional Information')
                    ->schema([
                        Components\TextEntry::make('verified_at')
                            ->dateTime()
                            ->placeholder('Not verified'),
                        Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}
