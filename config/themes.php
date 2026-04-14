<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    | Clean modern e-commerce theme — blue/cyan palette, gradient hero.
    */
    'default' => [
        'name' => 'Default Blue',
        'description' => 'Clean modern e-commerce theme with vibrant blue tones.',
        'preview' => 'images/themes/default.png',
        'fonts' => [
            'heading' => "'Instrument Sans', sans-serif",
            'body' => "'Instrument Sans', sans-serif",
            'google_import' => 'https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap',
        ],
        'colors' => [
            'primary' => '#3b82f6',
            'primary-dark' => '#2563eb',
            'primary-light' => '#60a5fa',
            'accent' => '#22d3ee',
            'surface' => '#f9fafb',
            'surface-dark' => '#111827',
            'on-primary' => '#ffffff',
            'on-surface-dark' => '#d1d5db',
        ],
        'hero_style' => 'gradient',
        'layout_style' => 'default',
        'product_card_style' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fashion & Luxury Theme
    |--------------------------------------------------------------------------
    | Elegant premium theme — dark/gold palette, serif headings, split hero.
    | Ideal for imported luxury goods, fashion boutiques, premium brands.
    */
    'luxury' => [
        'name' => 'Fashion & Luxury',
        'description' => 'Elegant premium theme with serif typography and gold accents.',
        'preview' => 'images/themes/luxury.png',
        'fonts' => [
            'heading' => "'Playfair Display', serif",
            'body' => "'Inter', sans-serif",
            'google_import' => 'https://fonts.bunny.net/css?family=playfair-display:400,500,600,700&family=inter:400,500,600&display=swap',
        ],
        'colors' => [
            'primary' => '#b48e3a',
            'primary-dark' => '#927023',
            'primary-light' => '#d4b45f',
            'accent' => '#b48e3a',
            'surface' => '#faf9f7',
            'surface-dark' => '#1c1917',
            'on-primary' => '#ffffff',
            'on-surface-dark' => '#d6d3d1',
        ],
        'hero_style' => 'split',
        'layout_style' => 'luxury',
        'product_card_style' => 'minimal',
    ],

    /*
    |--------------------------------------------------------------------------
    | Industrial / Supplies Theme
    |--------------------------------------------------------------------------
    | Clean professional theme — slate/blue palette, minimal hero.
    | Ideal for B2B supplies, stationery, printer consumables, hardware.
    */
    'industrial' => [
        'name' => 'Industrial & Supplies',
        'description' => 'Clean professional theme for B2B and industrial products.',
        'preview' => 'images/themes/industrial.png',
        'fonts' => [
            'heading' => "'Inter', sans-serif",
            'body' => "'Inter', sans-serif",
            'google_import' => 'https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap',
        ],
        'colors' => [
            'primary' => '#334155',
            'primary-dark' => '#1e293b',
            'primary-light' => '#64748b',
            'accent' => '#3b82f6',
            'surface' => '#f8fafc',
            'surface-dark' => '#0f172a',
            'on-primary' => '#ffffff',
            'on-surface-dark' => '#cbd5e1',
        ],
        'hero_style' => 'minimal',
        'layout_style' => 'compact',
        'product_card_style' => 'default',
    ],

];
