<?php
// app/Filament/Admin/Resources/RealTimeSessionResource/Pages/ListRealTimeSessions.php

namespace App\Filament\Admin\Resources\RealTimeSessionResource\Pages;

use App\Filament\Admin\Resources\RealTimeSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListRealTimeSessions extends ListRecords
{
    protected static string $resource = RealTimeSessionResource::class;
}
