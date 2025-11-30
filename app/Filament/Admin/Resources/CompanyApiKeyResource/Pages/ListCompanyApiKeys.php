<?php
namespace App\Filament\Admin\Resources\CompanyApiKeyResource\Pages;

use App\Filament\Admin\Resources\CompanyApiKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyApiKeys extends ListRecords
{
    protected static string $resource = CompanyApiKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
