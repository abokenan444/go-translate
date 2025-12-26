<?php
namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Partner Information')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'law_firm' => 'Law Firm',
                        'translation_agency' => 'Translation Agency',
                        'university' => 'University',
                        'corporate' => 'Corporate',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                    ])
                    ->required()
                    ->default('pending'),
            ])->columns(3),

            Forms\Components\Section::make('Contact Details')->schema([
                Forms\Components\TextInput::make('contact_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_phone')
                    ->tel()
                    ->maxLength(255),
            ])->columns(3),

            Forms\Components\Section::make('Partnership Settings')->schema([
                Forms\Components\Toggle::make('white_label')
                    ->label('White Label Enabled'),
                Forms\Components\TextInput::make('discount_rate')
                    ->numeric()
                    ->suffix('%')
                    ->default(0),
                Forms\Components\TextInput::make('custom_domain')
                    ->url()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo_url')
                    ->image()
                    ->directory('partners/logos'),
            ])->columns(2),

            Forms\Components\Section::make('Notes')->schema([
                Forms\Components\Textarea::make('notes')
                    ->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'law_firm',
                        'success' => 'translation_agency',
                        'warning' => 'university',
                        'info' => 'corporate',
                    ]),
                Tables\Columns\TextColumn::make('contact_email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('white_label')
                    ->boolean(),
                Tables\Columns\TextColumn::make('discount_rate')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'pending',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type'),
                Tables\Filters\SelectFilter::make('status'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
