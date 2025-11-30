<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['group' => 'general', 'type' => 'email', 'label' => 'Support Email', 'value' => 'support@culturaltranslate.com', 'order' => 1],
            ['group' => 'general', 'type' => 'phone', 'label' => 'Phone', 'value' => '+1 (555) 123-4567', 'order' => 2],
            ['group' => 'general', 'type' => 'address', 'label' => 'Address', 'value' => 'Remote-first • Global', 'order' => 3],
            ['group' => 'links', 'type' => 'link', 'label' => 'Help Center', 'value' => '/help-center', 'order' => 1],
        ];

        foreach ($items as $item) {
            DB::table('contact_settings')->updateOrInsert(
                ['group' => $item['group'], 'type' => $item['type'], 'label' => $item['label']],
                ['value' => $item['value'], 'order' => $item['order']]
            );
        }
    }
}
