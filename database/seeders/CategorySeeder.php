<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name_en' => 'Voltage protection devices',
                'name_ar' => 'أجهزة حماية الجهد',
                'description_en' => '',
                'description_ar' => '',
                'image' => 'categories/voltage_protection_devices.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_en' => 'Circuit breakers',
                'name_ar' => 'القواطع الكهربائية',
                'description_en' => '',
                'description_ar' => '',
                'image' => 'categories/circuit_breakers.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name_en' => 'Protection relays',
                'name_ar' => 'مرحلات الحماية',
                'description_en' => '',
                'description_ar' => '',
                'image' => 'categories/protection_relays.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_en' => 'Monitoring and signaling units',
                'name_ar' => 'وحدات المراقبة والإشارة',
                'description_en' => '',
                'description_ar' => '',
                'image' => 'categories/monitoring_and_signaling_units.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
