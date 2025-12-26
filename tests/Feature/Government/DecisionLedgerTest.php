<?php

namespace Tests\Feature\Government;

use App\Models\DecisionLedgerEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecisionLedgerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ledger_cannot_be_updated()
    {
        $event = DecisionLedgerEvent::create([
            'event_type' => 'certificate_issued',
            'certificate_id' => 1,
            'actor_user_id' => 1,
            'payload' => ['test' => 'data'],
            'hash' => hash('sha256', 'initial'),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ledger is append-only');

        $event->update(['event_type' => 'modified']);
    }

    /** @test */
    public function ledger_cannot_be_deleted()
    {
        $event = DecisionLedgerEvent::create([
            'event_type' => 'certificate_issued',
            'certificate_id' => 1,
            'actor_user_id' => 1,
            'payload' => ['test' => 'data'],
            'hash' => hash('sha256', 'initial'),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ledger is append-only');

        $event->delete();
    }

    /** @test */
    public function ledger_events_are_hash_chained()
    {
        // Create first event
        $event1 = DecisionLedgerEvent::create([
            'event_type' => 'certificate_issued',
            'certificate_id' => 1,
            'actor_user_id' => 1,
            'payload' => ['certificate_number' => 'CERT001'],
            'prev_hash' => null,
            'hash' => hash('sha256', 'event1'),
        ]);

        // Create second event
        $event2 = DecisionLedgerEvent::create([
            'event_type' => 'certificate_frozen',
            'certificate_id' => 1,
            'actor_user_id' => 1,
            'payload' => ['reason' => 'Investigation'],
            'prev_hash' => $event1->hash,
            'hash' => hash('sha256', 'event2'),
        ]);

        $this->assertEquals($event1->hash, $event2->prev_hash);
        $this->assertNotEquals($event1->hash, $event2->hash);
    }

    /** @test */
    public function chain_integrity_can_be_verified()
    {
        // Create chain of 5 events
        $prevHash = null;
        
        for ($i = 1; $i <= 5; $i++) {
            $hash = hash('sha256', "event{$i}");
            
            DecisionLedgerEvent::create([
                'event_type' => 'test_event',
                'certificate_id' => 1,
                'actor_user_id' => 1,
                'payload' => ['step' => $i],
                'prev_hash' => $prevHash,
                'hash' => $hash,
            ]);
            
            $prevHash = $hash;
        }

        // Verify chain
        $events = DecisionLedgerEvent::where('certificate_id', 1)
            ->orderBy('created_at')
            ->get();

        $isValid = true;
        $prevHash = null;

        foreach ($events as $event) {
            if ($event->prev_hash !== $prevHash) {
                $isValid = false;
                break;
            }
            $prevHash = $event->hash;
        }

        $this->assertTrue($isValid, 'Chain should be valid');
    }
}
