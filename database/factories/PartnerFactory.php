<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'logo' => null,
            'status' => 'active',
            'total_revenue' => $this->faker->randomFloat(2, 0, 100000),
            'commission_rate' => $this->faker->randomFloat(2, 5, 20),
            'pending_payout' => $this->faker->randomFloat(2, 0, 5000),
            'total_paid' => $this->faker->randomFloat(2, 0, 50000),
            'conversion_rate' => $this->faker->randomFloat(2, 0, 100),
            'certification_level' => $this->faker->randomElement(['bronze', 'silver', 'gold', 'platinum', null]),
            'certified_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'is_public' => true,
            'is_verified' => $this->faker->boolean(80),
            'specializations' => json_encode($this->faker->randomElements(['legal', 'medical', 'technical', 'business'], 2)),
            'language_pairs' => json_encode([
                ['source' => 'en', 'target' => 'ar'],
                ['source' => 'ar', 'target' => 'en'],
            ]),
            'overall_rating' => $this->faker->randomFloat(2, 3, 5),
            'quality_rating' => $this->faker->randomFloat(2, 3, 5),
            'speed_rating' => $this->faker->randomFloat(2, 3, 5),
            'communication_rating' => $this->faker->randomFloat(2, 3, 5),
            'total_reviews' => $this->faker->numberBetween(0, 100),
            'total_projects' => $this->faker->numberBetween(0, 500),
            'completed_projects' => function (array $attributes) {
                return $this->faker->numberBetween(0, $attributes['total_projects']);
            },
            'success_rate' => function (array $attributes) {
                return $attributes['total_projects'] > 0
                    ? ($attributes['completed_projects'] / $attributes['total_projects']) * 100
                    : 0;
            },
        ];
    }

    public function certified(): static
    {
        return $this->state(fn (array $attributes) => [
            'certification_level' => $this->faker->randomElement(['bronze', 'silver', 'gold', 'platinum']),
            'certified_at' => now()->subMonths($this->faker->numberBetween(1, 12)),
        ]);
    }

    public function highPerforming(): static
    {
        return $this->state(fn (array $attributes) => [
            'overall_rating' => $this->faker->randomFloat(2, 4.5, 5),
            'quality_rating' => $this->faker->randomFloat(2, 4.5, 5),
            'speed_rating' => $this->faker->randomFloat(2, 4.5, 5),
            'communication_rating' => $this->faker->randomFloat(2, 4.5, 5),
            'success_rate' => $this->faker->randomFloat(2, 95, 100),
        ]);
    }
}
