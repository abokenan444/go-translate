<?php

namespace App\Filament\Resources\GovernmentRegistrationResource\Pages;

use App\Filament\Resources\GovernmentRegistrationResource;
use App\Models\GovernmentRegistration;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ReviewGovernmentRegistration extends Page
{
    protected static string $resource = GovernmentRegistrationResource::class;

    protected static string $view = 'filament.resources.government-registration-resource.pages.review';

    public GovernmentRegistration $record;

    public ?array $data = [];

    public function mount(int | string $record): void
    {
        $this->record = GovernmentRegistration::findOrFail($record);
        
        // Mark as under review if pending
        if ($this->record->isPending()) {
            $this->record->markUnderReview(auth()->user());
        }

        $this->form->fill([
            'review_notes' => $this->record->review_notes,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Review')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Applicant Information')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Placeholder::make('name')
                                            ->content($this->record->name),
                                        Forms\Components\Placeholder::make('email')
                                            ->content($this->record->email),
                                        Forms\Components\Placeholder::make('phone')
                                            ->content($this->record->phone ?? '-'),
                                        Forms\Components\Placeholder::make('job_title')
                                            ->content($this->record->job_title),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Entity Details')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Placeholder::make('entity_name')
                                            ->label('Entity Name')
                                            ->content($this->record->entity_name),
                                        Forms\Components\Placeholder::make('entity_type')
                                            ->label('Entity Type')
                                            ->content(GovernmentRegistration::ENTITY_TYPES[$this->record->entity_type] ?? '-'),
                                        Forms\Components\Placeholder::make('country')
                                            ->content($this->record->country),
                                        Forms\Components\Placeholder::make('department')
                                            ->content($this->record->department ?? '-'),
                                    ]),
                                Forms\Components\Placeholder::make('official_website_url')
                                    ->label('Official Website')
                                    ->content(function () {
                                        if ($this->record->official_website_url) {
                                            return "<a href='{$this->record->official_website_url}' target='_blank' class='text-primary-600 hover:underline'>{$this->record->official_website_url}</a>";
                                        }
                                        return '-';
                                    })
                                    ->html()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Documents')
                            ->badge(count($this->record->documents ?? []))
                            ->schema([
                                Forms\Components\View::make('filament.components.document-viewer')
                                    ->viewData(['documents' => $this->record->documents ?? []])
                            ]),

                        Forms\Components\Tabs\Tab::make('Review Decision')
                            ->schema([
                                Forms\Components\Textarea::make('review_notes')
                                    ->label('Review Notes')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('approve')
                ->label('✓ Approve Registration')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Government Registration')
                ->modalDescription('Are you sure you want to approve this government registration? This will grant the entity verified status.')
                ->action(function () {
                    $this->record->approve(
                        auth()->user(),
                        $this->data['review_notes'] ?? null
                    );

                    Notification::make()
                        ->success()
                        ->title('Registration Approved')
                        ->body('The government registration has been approved successfully.')
                        ->send();

                    return redirect()->route('filament.admin.resources.government-registrations.index');
                }),

            Forms\Components\Actions\Action::make('reject')
                ->label('✗ Reject Registration')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Government Registration')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->placeholder('Explain why this registration is being rejected...')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $this->record->reject(
                        auth()->user(),
                        $data['rejection_reason']
                    );

                    Notification::make()
                        ->danger()
                        ->title('Registration Rejected')
                        ->body('The government registration has been rejected.')
                        ->send();

                    return redirect()->route('filament.admin.resources.government-registrations.index');
                }),

            Forms\Components\Actions\Action::make('requestInfo')
                ->label('⚠ Request More Information')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Request Additional Information')
                ->form([
                    Forms\Components\Textarea::make('info_request')
                        ->label('What additional information is needed?')
                        ->placeholder('Specify what information the applicant needs to provide...')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $this->record->requestMoreInfo(
                        auth()->user(),
                        $data['info_request']
                    );

                    Notification::make()
                        ->warning()
                        ->title('More Information Requested')
                        ->body('The applicant will be notified to provide additional information.')
                        ->send();

                    return redirect()->route('filament.admin.resources.government-registrations.index');
                }),

            Forms\Components\Actions\Action::make('cancel')
                ->label('Cancel')
                ->color('gray')
                ->url(route('filament.admin.resources.government-registrations.index')),
        ];
    }

    public function getTitle(): string
    {
        return 'Review Government Registration';
    }
}
