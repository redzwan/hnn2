<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Audio & Headphones',
                'description' => 'Headphones, earbuds, speakers, and audio accessories for immersive sound experiences.',
                'banner' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1920&h=600&fit=crop',
                'sort_order' => 1,
            ],
            [
                'name' => 'Keyboards & Mice',
                'description' => 'Mechanical keyboards, wireless mice, and input devices for enhanced productivity.',
                'banner' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=1920&h=600&fit=crop',
                'sort_order' => 2,
            ],
            [
                'name' => 'Monitors & Displays',
                'description' => 'Ultrawide monitors, display accessories, and screen solutions for any setup.',
                'banner' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=1920&h=600&fit=crop',
                'sort_order' => 3,
            ],
            [
                'name' => 'Office Furniture',
                'description' => 'Ergonomic chairs, standing desks, and office accessories for comfortable workspaces.',
                'banner' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=1920&h=600&fit=crop',
                'sort_order' => 4,
            ],
            [
                'name' => 'Storage & Drives',
                'description' => 'Portable SSDs, external hard drives, USB flash drives, and storage solutions.',
                'banner' => 'https://images.unsplash.com/photo-1597848212624-a19eb35e2651?w=1920&h=600&fit=crop',
                'sort_order' => 5,
            ],
            [
                'name' => 'Webcams & Streaming',
                'description' => '4K webcams, ring lights, and streaming equipment for content creators.',
                'banner' => 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=1920&h=600&fit=crop',
                'sort_order' => 6,
            ],
            [
                'name' => 'Hubs & Adapters',
                'description' => 'USB-C hubs, docking stations, and adapters for seamless connectivity.',
                'banner' => 'https://images.unsplash.com/photo-1625723044792-44de16ccb4e9?w=1920&h=600&fit=crop',
                'sort_order' => 7,
            ],
            [
                'name' => 'Lighting',
                'description' => 'Smart desk lamps, LED strips, and ambient lighting for home offices.',
                'banner' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=1920&h=600&fit=crop',
                'sort_order' => 8,
            ],
            [
                'name' => 'Cables & Accessories',
                'description' => 'Charging cables, cable management, and essential tech accessories.',
                'banner' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1920&h=600&fit=crop',
                'sort_order' => 9,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'banner' => $category['banner'],
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                ]
            );
        }
    }
}
