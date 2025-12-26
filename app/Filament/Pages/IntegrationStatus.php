<?php

namespace App\Filament\Pages;

use App\Services\IntegrationService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class IntegrationStatus extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string $view = 'filament.pages.integration-status';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $title = 'Integration Status';

    protected static ?int $navigationSort = 99;

    public array $integrations = [];

    public function mount(): void
    {
        $service = app(IntegrationService::class);
        $this->integrations = $service->getIntegrationStatuses();
    }

    public function testSlack(): void
    {
        $service = app(IntegrationService::class);
        $result = $service->testSlack();

        if ($result['success']) {
            Notification::make()
                ->title('Slack connection successful!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Slack connection failed')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }
}
