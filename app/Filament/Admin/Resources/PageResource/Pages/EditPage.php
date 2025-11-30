<?php
namespace App\Filament\Admin\Resources\PageResource\Pages;
use App\Filament\Admin\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
