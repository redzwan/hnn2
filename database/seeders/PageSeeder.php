<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'description' => 'Learn more about our company and our commitment to quality.',
                'is_active' => true,
                'sort_order' => 1,
                'content_blocks' => [
                    [
                        'title' => 'Who We Are',
                        'content' => '<p>We are passionate about delivering the best products to our customers. With years of experience in e-commerce, we understand what matters most - quality, reliability, and customer satisfaction.</p>',
                        'image' => null,
                    ],
                    [
                        'title' => 'Our Mission',
                        'content' => '<p>Our mission is simple: to provide an exceptional shopping experience with top-quality products, competitive prices, and outstanding customer service. Every customer matters to us.</p>',
                        'image' => null,
                    ],
                ],
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'description' => 'Get in touch with us. We\'re here to help!',
                'is_active' => true,
                'sort_order' => 2,
                'content_blocks' => [
                    [
                        'title' => 'Get In Touch',
                        'content' => '<p>We\'d love to hear from you! Whether you have questions about our products, need help with an order, or just want to say hello, feel free to reach out.</p><p><strong>Email:</strong> support@myshop.com</p><p><strong>Phone:</strong> +1 234 567 890</p>',
                        'image' => null,
                    ],
                    [
                        'title' => 'Office Hours',
                        'content' => '<p>Monday - Friday: 9:00 AM - 6:00 PM</p><p>Saturday: 10:00 AM - 4:00 PM</p><p>Sunday: Closed</p>',
                        'image' => null,
                    ],
                ],
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
