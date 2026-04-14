<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Vanilo\Product\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin / test user
        User::firstOrCreate(
            ['email' => 'admin@myshop.com'],
            [
                'name'     => 'Admin',
                'password' => 'password',
            ]
        );

        // Demo products
        $products = [
            ['name' => 'Wireless Noise-Cancelling Headphones', 'sku' => 'HDPH-001', 'price' => 599.00,  'description' => '<p>Premium wireless headphones with active noise cancellation. Up to 30 hours battery life with fast charging.</p>'],
            ['name' => 'Mechanical Keyboard Pro',              'sku' => 'KB-002',   'price' => 349.00,  'description' => '<p>Full-size mechanical keyboard with RGB backlight. Tactile switches for the perfect typing experience.</p>'],
            ['name' => 'Ultra-Wide Monitor 34"',               'sku' => 'MON-003',  'price' => 1899.00, 'description' => '<p>34-inch curved ultra-wide monitor with 144Hz refresh rate and HDR support. Perfect for work and gaming.</p>'],
            ['name' => 'Ergonomic Office Chair',               'sku' => 'CHR-004',  'price' => 1299.00, 'description' => '<p>Fully adjustable ergonomic chair with lumbar support. Designed for all-day comfort.</p>'],
            ['name' => 'USB-C Hub 7-in-1',                     'sku' => 'HUB-005',  'price' => 129.00,  'description' => '<p>7-in-1 USB-C hub with 4K HDMI, USB-A ports, SD card reader and 100W PD charging.</p>'],
            ['name' => 'Portable SSD 1TB',                    'sku' => 'SSD-006',  'price' => 299.00,  'description' => '<p>Compact 1TB portable SSD with read speeds up to 1050MB/s. Shock and drop resistant.</p>'],
            ['name' => 'Webcam 4K Streaming',                 'sku' => 'CAM-007',  'price' => 449.00,  'description' => '<p>Professional 4K webcam with auto-focus, low-light correction and built-in ring light.</p>'],
            ['name' => 'Smart Desk Lamp',                      'sku' => 'LMP-008',  'price' => 199.00,  'description' => '<p>Adjustable smart LED desk lamp with wireless charging pad, USB ports and eye-care modes.</p>'],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'name'        => $data['name'],
                    'slug'        => Str::slug($data['name']),
                    'price'       => $data['price'],
                    'description' => $data['description'],
                    'state'       => 'active',
                ]
            );
        }

        $this->command->info('✓ Admin user: admin@myshop.com / password');
        $this->command->info('✓ Seeded ' . count($products) . ' demo products');
    }
}
