<?php
namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'المستخدمون الجدد';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // الحصول على بيانات آخر 12 شهر
        $data = User::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = date('M Y', strtotime($item->month . '-01'));
            $values[] = $item->count;
        }

        // إذا لم توجد بيانات، أضف بيانات تجريبية
        if (empty($labels)) {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $values[] = rand(5, 25);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'مستخدمون جدد',
                    'data' => $values,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
