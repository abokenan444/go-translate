<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareersMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Careers menu item already exists
        $exists = DB::table('menu_items')
            ->where('url', '/careers')
            ->exists();
        
        if (!$exists) {
            // Get the maximum order for footer items
            $maxOrder = DB::table('menu_items')
                ->where('location', 'footer')
                ->max('order') ?? 0;
            
            DB::table('menu_items')->insert([
                'title' => 'Careers',
                'url' => '/careers',
                'location' => 'footer',
                'parent_id' => null,
                'order' => $maxOrder + 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Careers menu item added to footer!');
        } else {
            $this->command->info('Careers menu item already exists.');
        }
        
        // Also add Arabic version if needed
        $existsAr = DB::table('menu_items')
            ->where('url', '/careers')
            ->where('title', 'وظائف')
            ->exists();
        
        if (!$existsAr) {
            $maxOrder = DB::table('menu_items')
                ->where('location', 'footer')
                ->max('order') ?? 0;
                
            DB::table('menu_items')->insert([
                'title' => 'وظائف',
                'url' => '/careers',
                'location' => 'footer',
                'parent_id' => null,
                'order' => $maxOrder + 2,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Arabic careers menu item added!');
        }
    }
}
