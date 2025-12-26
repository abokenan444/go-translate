<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample partners
        Partner::factory()->count(10)->create();
        
        // Create some certified partners
        Partner::factory()->certified()->highPerforming()->count(5)->create();
    }
}
