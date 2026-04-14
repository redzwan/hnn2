<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerator
{
    public function generate(): void
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(
            Url::create(url('/'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        $sitemap->add(
            Url::create(url('/products'))
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Active products
        Product::actives()
            ->where('noindex', false)
            ->latest('updated_at')
            ->get()
            ->each(function (Product $product) use ($sitemap) {
                $sitemap->add(
                    Url::create(url("/products/{$product->slug}"))
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($product->updated_at)
                );
            });

        // Active categories
        Category::active()
            ->get()
            ->each(function (Category $category) use ($sitemap) {
                $sitemap->add(
                    Url::create(url("/category/{$category->slug}"))
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        // CMS pages
        Page::active()
            ->get()
            ->each(function (Page $page) use ($sitemap) {
                $sitemap->add(
                    Url::create(url("/{$page->slug}"))
                        ->setPriority(0.6)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($page->updated_at)
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        Setting::set('sitemap.last_generated', now()->toIso8601String());
        Setting::set('sitemap.url_count', count($sitemap->getTags()));
    }

    public function lastGenerated(): ?string
    {
        return Setting::get('sitemap.last_generated');
    }

    public function urlCount(): int
    {
        return (int) Setting::get('sitemap.url_count', 0);
    }

    public function exists(): bool
    {
        return file_exists(public_path('sitemap.xml'));
    }
}
