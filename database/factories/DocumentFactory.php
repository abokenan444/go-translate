<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'reference_number' => 'DOC-' . now()->year . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'source_language' => 'en',
            'target_language' => 'ar',
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'rejected']),
            'translator_id' => User::factory(),
            'government_verified' => false,
            'verification_date' => null,
            'assigned_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'completed_at' => null,
            'revision_count' => 0,
            'rating' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
            'rating' => $this->faker->randomFloat(2, 3, 5),
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'government_verified' => true,
            'verification_date' => now(),
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'assigned_at' => now()->subHours($this->faker->numberBetween(1, 48)),
        ]);
    }
}
