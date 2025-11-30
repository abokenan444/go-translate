<?php

namespace App\Filament\Resources\SecurityLogResource\Pages;

use App\Filament\Resources\SecurityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSecurityLogs extends ListRecords
{
    protected static string $resource = SecurityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clear_old_logs')
                ->label('حذف السجلات القديمة')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('حذف السجلات القديمة')
                ->modalDescription('سيتم حذف السجلات الأقدم من 30 يومًا. هل أنت متأكد؟')
                ->action(function () {
                    \App\Models\SecurityLog::where('created_at', '<', now()->subDays(30))->delete();
                    \Filament\Notifications\Notification::make()
                        ->title('تم حذف السجلات القديمة')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل'),
            
            'today' => Tab::make('اليوم')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', today()))
                ->badge(\App\Models\SecurityLog::whereDate('created_at', today())->count()),
            
            'high_severity' => Tab::make('خطورة عالية')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('severity', ['high', 'critical']))
                ->badge(\App\Models\SecurityLog::whereIn('severity', ['high', 'critical'])->count())
                ->badgeColor('danger'),
            
            'sql_injection' => Tab::make('SQL Injection')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('attack_type', 'SQL Injection'))
                ->badge(\App\Models\SecurityLog::where('attack_type', 'SQL Injection')->count())
                ->badgeColor('danger'),
            
            'blocked' => Tab::make('محظور')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('blocked', true))
                ->badge(\App\Models\SecurityLog::where('blocked', true)->count())
                ->badgeColor('success'),
        ];
    }
}
