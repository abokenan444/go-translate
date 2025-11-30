<?php

namespace App\Filament\Admin\Resources\ContactSettingResource\Pages\ContactSettingResource\Pages;

use App\Filament\Admin\Resources\ContactSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactSettings extends ListRecords
{
    protected static string $resource = ContactSettingResource::class;
}
