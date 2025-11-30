<?php
// app/Filament/Admin/Resources/RealTimeTurnResource/Pages/ListRealTimeTurns.php

namespace App\Filament\Admin\Resources\RealTimeTurnResource\Pages;

use App\Filament\Admin\Resources\RealTimeTurnResource;
use Filament\Resources\Pages\ListRecords;

class ListRealTimeTurns extends ListRecords
{
    protected static string $resource = RealTimeTurnResource::class;
}
