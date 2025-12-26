<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Subscriptions';

    protected static ?string $navigationLabel = 'Subscription Plans';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Plan Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Plan Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Used in URLs (e.g., basic, professional, enterprise)'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(1000),

                        Forms\Components\TextInput::make('price')
                            ->label('Price (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->step(0.01),

                        Forms\Components\Select::make('billing_cycle')
                            ->label('Billing Cycle')
                            ->options([
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly',
                                'one-time' => 'One Time',
                            ])
                            ->default('monthly')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Stripe Integration')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_price_id')
                            ->label('Stripe Price ID')
                            ->placeholder('price_xxxxxxxxxxxxxx')
                            ->helperText('Get this from your Stripe Dashboard > Products'),

                        Forms\Components\TextInput::make('stripe_product_id')
                            ->label('Stripe Product ID')
                            ->placeholder('prod_xxxxxxxxxxxxxx')
                            ->helperText('Optional: Stripe Product ID'),
                    ])->columns(2),

                Forms\Components\Section::make('Plan Limits')
                    ->schema([
                        Forms\Components\TextInput::make('words_limit')
                            ->label('Words Limit per Month')
                            ->numeric()
                            ->default(0)
                            ->helperText('0 = Unlimited'),

                        Forms\Components\TextInput::make('translations_limit')
                            ->label('Translations Limit per Month')
                            ->numeric()
                            ->default(0)
                            ->helperText('0 = Unlimited'),

                        Forms\Components\TextInput::make('api_calls_limit')
                            ->label('API Calls Limit per Month')
                            ->numeric()
                            ->default(0)
                            ->helperText('0 = Unlimited'),

                        Forms\Components\TextInput::make('team_members_limit')
                            ->label('Team Members Limit')
                            ->numeric()
                            ->default(1),
                    ])->columns(2),

                Forms\Components\Section::make('Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->label('Plan Features')
                            ->schema([
                                Forms\Components\TextInput::make('feature')
                                    ->label('Feature')
                                    ->required(),
                                Forms\Components\Toggle::make('included')
                                    ->label('Included')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->defaultItems(3)
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive plans will not be shown to users'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->helperText('Featured plans are highlighted on pricing page'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_cycle')
                    ->label('Billing')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'monthly' => 'info',
                        'yearly' => 'success',
                        'one-time' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('words_limit')
                    ->label('Words Limit')
                    ->formatStateUsing(fn ($state) => $state == 0 ? 'Unlimited' : number_format($state)),

                Tables\Columns\IconColumn::make('stripe_price_id')
                    ->label('Stripe')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->getStateUsing(fn ($record) => !empty($record->stripe_price_id)),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('subscriptions_count')
                    ->label('Subscribers')
                    ->counts('subscriptions')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (SubscriptionPlan $record) {
                        $newPlan = $record->replicate();
                        $newPlan->name = $record->name . ' (Copy)';
                        $newPlan->slug = $record->slug . '-copy';
                        $newPlan->stripe_price_id = null;
                        $newPlan->save();
                    })
                    ->requiresConfirmation(),
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
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
