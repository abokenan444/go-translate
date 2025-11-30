<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class LanguageSwitcherWidget extends Widget
{
    protected static string $view = 'filament.widgets.language-switcher';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = -1;
}
