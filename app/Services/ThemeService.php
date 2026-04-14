<?php

namespace App\Services;

use App\Models\Setting;

class ThemeService
{
    /**
     * Get the active theme configuration array.
     *
     * @return array{name: string, description: string, preview: string, fonts: array, colors: array, hero_style: string, layout_style: string, product_card_style: string}
     */
    public function active(): array
    {
        $key = $this->activeKey();

        return config("themes.{$key}", config('themes.default'));
    }

    /**
     * Get the active theme key.
     */
    public function activeKey(): string
    {
        return Setting::get('theme.active', 'default');
    }

    /**
     * Get all available themes.
     *
     * @return array<string, array>
     */
    public function all(): array
    {
        return config('themes', []);
    }

    /**
     * Generate inline CSS variable declarations for the active theme.
     */
    /**
     * Generate inline CSS variable declarations for the active theme.
     * Maps theme colors to --theme-*-rgb variables referenced by @theme in app.css.
     */
    public function cssVariables(): string
    {
        $theme = $this->active();

        return collect($theme['colors'])
            ->map(fn (string $value, string $key) => "--theme-{$key}-rgb: {$value}")
            ->implode('; ');
    }

    /**
     * Get the Google Fonts import URL for the active theme.
     */
    public function fontUrl(): string
    {
        return $this->active()['fonts']['google_import'] ?? '';
    }

    /**
     * Get the heading font-family for the active theme.
     */
    public function headingFont(): string
    {
        return $this->active()['fonts']['heading'] ?? "'Instrument Sans', sans-serif";
    }

    /**
     * Get the body font-family for the active theme.
     */
    public function bodyFont(): string
    {
        return $this->active()['fonts']['body'] ?? "'Instrument Sans', sans-serif";
    }

    /**
     * Get the hero section style key.
     */
    public function heroStyle(): string
    {
        return $this->active()['hero_style'] ?? 'gradient';
    }

    /**
     * Get the product card style key.
     */
    public function productCardStyle(): string
    {
        return $this->active()['product_card_style'] ?? 'default';
    }
}
