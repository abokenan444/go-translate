<?php

namespace App\Filament\SuperAdmin\Resources\PermissionResource\Pages;

use App\Filament\SuperAdmin\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
