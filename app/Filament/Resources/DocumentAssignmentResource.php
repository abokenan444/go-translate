<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentAssignmentResource\Pages;
use App\Models\DocumentAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentAssignmentResource extends Resource
{
    protected static ?string $model = DocumentAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    
    protected static ?string $navigationLabel = 'Document Assignments';
    
    protected static ?string $navigationGroup = 'Partner Governance';
    
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'offered')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Assignment Details')
                    ->schema([
                        Forms\Components\Select::make('document_id')
                            ->relationship('document', 'id')
                            ->required(),
                        Forms\Components\Select::make('partner_id')
                            ->relationship('partner', 'display_name')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'offered' => 'Offered',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                                'timed_out' => 'Timed Out',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('attempt_no')
                            ->numeric()
                            ->default(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Timestamps')
                    ->schema([
                        Forms\Components\DateTimePicker::make('offered_at'),
                        Forms\Components\DateTimePicker::make('expires_at'),
                        Forms\Components\DateTimePicker::make('responded_at'),
                        Forms\Components\DateTimePicker::make('accepted_at'),
                        Forms\Components\DateTimePicker::make('completed_at'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Additional Info')
                    ->schema([
                        Forms\Components\Textarea::make('reason')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('document.id')
                    ->label('Document')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.display_name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'offered' => 'warning',
                        'accepted' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        'timed_out' => 'gray',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('attempt_no')
                    ->label('Attempt')
                    ->sortable(),
                Tables\Columns\TextColumn::make('offered_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at && $record->expires_at->isPast() ? 'danger' : null),
                Tables\Columns\TextColumn::make('accepted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'offered' => 'Offered',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'timed_out' => 'Timed Out',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now())->where('status', 'offered'))
                    ->label('Expired Offers'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDocumentAssignments::route('/'),
            'create' => Pages\CreateDocumentAssignment::route('/create'),
            'edit' => Pages\EditDocumentAssignment::route('/{record}/edit'),
            'view' => Pages\ViewDocumentAssignment::route('/{record}'),
        ];
    }
}
