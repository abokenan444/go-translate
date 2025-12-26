<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Models\Document;
use App\Services\TranslatorPerformanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslatorPerformanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $translator;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = app(TranslatorPerformanceService::class);
        $this->translator = User::factory()->create([
            'role' => 'translator',
            'status' => 'active',
        ]);
    }

    public function test_calculates_performance_score()
    {
        // Create some completed documents
        Document::factory()->count(5)->create([
            'translator_id' => $this->translator->id,
            'status' => 'completed',
            'rating' => 4.5,
            'completed_at' => now(),
            'assigned_at' => now()->subHours(12),
        ]);

        $result = $this->service->calculatePerformanceScore($this->translator);

        $this->assertArrayHasKey('overall_score', $result);
        $this->assertArrayHasKey('breakdown', $result);
        $this->assertArrayHasKey('metrics', $result);
        $this->assertArrayHasKey('level', $result);
        
        $this->assertGreaterThan(0, $result['overall_score']);
        $this->assertLessThanOrEqual(100, $result['overall_score']);
    }

    public function test_determines_correct_level()
    {
        Document::factory()->count(10)->create([
            'translator_id' => $this->translator->id,
            'status' => 'completed',
            'rating' => 5.0,
            'completed_at' => now(),
            'assigned_at' => now()->subHours(8),
        ]);

        $result = $this->service->calculatePerformanceScore($this->translator);

        $this->assertContains($result['level'], ['Elite', 'Expert', 'Professional', 'Intermediate', 'Beginner']);
    }

    public function test_updates_performance_cache()
    {
        Document::factory()->count(3)->create([
            'translator_id' => $this->translator->id,
            'status' => 'completed',
            'rating' => 4.0,
        ]);

        $this->service->updatePerformanceCache($this->translator);

        $this->assertDatabaseHas('translator_performance', [
            'translator_id' => $this->translator->id,
        ]);
    }

    public function test_gets_top_performers()
    {
        // Create multiple translators with different performance
        $translator1 = User::factory()->create(['role' => 'translator', 'status' => 'active']);
        $translator2 = User::factory()->create(['role' => 'translator', 'status' => 'active']);

        Document::factory()->count(10)->create([
            'translator_id' => $translator1->id,
            'status' => 'completed',
            'rating' => 5.0,
        ]);

        Document::factory()->count(5)->create([
            'translator_id' => $translator2->id,
            'status' => 'completed',
            'rating' => 3.0,
        ]);

        $topPerformers = $this->service->getTopPerformers(2);

        $this->assertCount(2, $topPerformers);
        $this->assertGreaterThan($topPerformers[1]['score'], $topPerformers[0]['score']);
    }
}
