<?php
namespace App\Filament\Admin\Resources\BrandVoiceResource\Pages;
use App\Filament\Admin\Resources\BrandVoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListBrandVoices extends ListRecords
{
    protected static string $resource = BrandVoiceResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
