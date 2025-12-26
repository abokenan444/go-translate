<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GovInviteResource\Pages;
use App\Models\GovInvite;
use App\Models\GovEntity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class GovInviteResource extends Resource
{
    protected static ?string $model = GovInvite::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationLabel = 'Gov Invitations';
    
    protected static ?string $navigationGroup = 'Government System';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invitation Details')
                    ->schema([
                        Forms\Components\Select::make('gov_entity_id')
                            ->label('Government Entity')
                            ->relationship('entity', 'name_en')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('invited_name')
                            ->label('Invited Person Name')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'gov_client_operator' => 'Government Client Operator',
                                'gov_client_supervisor' => 'Government Client Supervisor',
                                'gov_authority_officer' => 'Authority Officer',
                                'gov_authority_supervisor' => 'Authority Supervisor',
                            ])
                            ->required(),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expiration Date')
                            ->required()
                            ->default(fn () => now()->addDays(config('government.invite_expiry_days', 30)))
                            ->minDate(now())
                            ->native(false),
                        
                        Forms\Components\Textarea::make('metadata')
                            ->label('Additional Notes (JSON)')
                            ->helperText('Optional metadata in JSON format')
                            ->rows(3),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('entity.name_en')
                    ->label('Entity')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('invited_name')
                    ->label('Name')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'gov_authority_supervisor' => 'danger',
                        'gov_authority_officer' => 'warning',
                        'gov_client_supervisor' => 'info',
                        'gov_client_operator' => 'success',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('token')
                    ->label('Token')
                    ->limit(20)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function (GovInvite $record): string {
                        if ($record->used_at) {
                            return 'used';
                        }
                        if ($record->expires_at <= now()) {
                            return 'expired';
                        }
                        return 'valid';
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'used' => 'heroicon-o-check-circle',
                        'expired' => 'heroicon-o-x-circle',
                        'valid' => 'heroicon-o-clock',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'used' => 'success',
                        'expired' => 'danger',
                        'valid' => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('used_at')
                    ->label('Used At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Used By')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('inviter.name')
                    ->label('Invited By')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gov_entity_id')
                    ->label('Entity')
                    ->relationship('entity', 'name_en'),
                
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'gov_client_operator' => 'Client Operator',
                        'gov_client_supervisor' => 'Client Supervisor',
                        'gov_authority_officer' => 'Authority Officer',
                        'gov_authority_supervisor' => 'Authority Supervisor',
                    ]),
                
                Tables\Filters\Filter::make('valid')
                    ->label('Valid Only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('used_at')->where('expires_at', '>', now())),
                
                Tables\Filters\Filter::make('used')
                    ->label('Used')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('used_at')),
                
                Tables\Filters\Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query->whereNull('used_at')->where('expires_at', '<=', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('copy_link')
                    ->label('Copy Link')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->visible(fn (GovInvite $record): bool => $record->isValid())
                    ->action(function (GovInvite $record) {
                        $url = route('government.register', ['token' => $record->token]);
                        // Copy to clipboard (client-side handled by Filament)
                        return $url;
                    })
                    ->url(fn (GovInvite $record): string => route('government.register', ['token' => $record->token]))
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (GovInvite $record): bool => $record->isValid())
                    ->requiresConfirmation()
                    ->action(function (GovInvite $record) {
                        $record->update(['expires_at' => now()]);
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListGovInvites::route('/'),
            'create' => Pages\CreateGovInvite::route('/create'),
            'view' => Pages\ViewGovInvite::route('/{record}'),
            'edit' => Pages\EditGovInvite::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereNull('used_at')
            ->where('expires_at', '>', now())
            ->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
