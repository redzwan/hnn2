<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\SitemapGenerator;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'SEO Settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'SEO Settings';

    protected static ?string $slug = 'seo-settings';

    protected static string $view = 'filament.pages.seo-settings';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'meta_title' => Setting::get('seo.meta_title', ''),
            'meta_description' => Setting::get('seo.meta_description', ''),
            'meta_keywords' => Setting::get('seo.meta_keywords', ''),
            'google_verification' => Setting::get('seo.google_verification', ''),
            'bing_verification' => Setting::get('seo.bing_verification', ''),
            'gtm_id' => Setting::get('seo.gtm_id', ''),
            'robots_txt' => Setting::get('seo.robots_txt', $this->defaultRobotsTxt()),
            'noindex_site' => (bool) Setting::get('seo.noindex_site', false),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')->schema([
            Section::make('Default Meta Tags')
                ->description('Used as fallback on pages without specific SEO fields.')
                ->icon('heroicon-o-tag')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Default Meta Title')
                        ->placeholder('My Online Shop – Best Deals in Malaysia')
                        ->maxLength(70)
                        ->helperText('Recommended: 50–70 characters.')
                        ->suffixAction(
                            \Filament\Forms\Components\Actions\Action::make('charCount')
                                ->label(fn ($state) => strlen($state ?? '').'/70')
                                ->disabled()
                        ),
                    Textarea::make('meta_description')
                        ->label('Default Meta Description')
                        ->placeholder('Shop the best electronics, accessories and more at unbeatable prices.')
                        ->rows(3)
                        ->maxLength(160)
                        ->helperText('Recommended: 120–160 characters.'),
                    TextInput::make('meta_keywords')
                        ->label('Keywords')
                        ->placeholder('online shop, electronics, Malaysia, deals')
                        ->helperText('Comma-separated. Less important for modern SEO but still used by some engines.'),
                ])->columns(1),

            Section::make('Search Engine Access')
                ->description('Control how crawlers access your site.')
                ->icon('heroicon-o-shield-check')
                ->schema([
                    Toggle::make('noindex_site')
                        ->label('Block search engines (noindex entire site)')
                        ->helperText('Turn this ON while your site is in development. Turn OFF before going live.')
                        ->default(false),
                    Textarea::make('robots_txt')
                        ->label('robots.txt Content')
                        ->rows(8)
                        ->extraInputAttributes(['class' => 'font-mono text-sm'])
                        ->helperText('Controls which crawlers can access which paths. Your sitemap URL is appended automatically.'),
                ])->columns(1),

            Section::make('Search Engine Verification')
                ->description('Verify ownership with Google Search Console, Bing Webmaster Tools, etc.')
                ->icon('heroicon-o-check-badge')
                ->schema([
                    TextInput::make('google_verification')
                        ->label('Google Search Console Verification Code')
                        ->placeholder('abc123xyz...')
                        ->helperText('The content value from the <meta name="google-site-verification"> tag.')
                        ->prefixIcon('heroicon-o-magnifying-glass'),
                    TextInput::make('bing_verification')
                        ->label('Bing Webmaster Verification Code')
                        ->placeholder('abc123xyz...')
                        ->helperText('The content value from the <meta name="msvalidate.01"> tag.')
                        ->prefixIcon('heroicon-o-globe-alt'),
                ])->columns(2),

            Section::make('Analytics & Tracking')
                ->description('Add tracking scripts to all pages.')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextInput::make('gtm_id')
                        ->label('Google Tag Manager ID')
                        ->placeholder('GTM-XXXXXXX')
                        ->helperText('Injected into every page. Use GTM to manage GA4, Meta Pixel, and more.')
                        ->prefixIcon('heroicon-o-code-bracket'),
                ])->columns(1),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewSitemap')
                ->label('View sitemap.xml')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(url('/sitemap.xml'))
                ->openUrlInNewTab()
                ->color('gray'),
            Action::make('regenerateSitemap')
                ->label('Regenerate Sitemap')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalDescription('This will rebuild sitemap.xml from all active products, categories and pages.')
                ->action(fn () => $this->regenerateSitemap()),
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('seo.meta_title', $data['meta_title'] ?? '');
        Setting::set('seo.meta_description', $data['meta_description'] ?? '');
        Setting::set('seo.meta_keywords', $data['meta_keywords'] ?? '');
        Setting::set('seo.google_verification', $data['google_verification'] ?? '');
        Setting::set('seo.bing_verification', $data['bing_verification'] ?? '');
        Setting::set('seo.gtm_id', $data['gtm_id'] ?? '');
        Setting::set('seo.robots_txt', $data['robots_txt'] ?? $this->defaultRobotsTxt());
        Setting::set('seo.noindex_site', (bool) ($data['noindex_site'] ?? false));

        Notification::make()
            ->title('SEO settings saved successfully!')
            ->success()
            ->send();
    }

    public function regenerateSitemap(): void
    {
        try {
            app(SitemapGenerator::class)->generate();

            Notification::make()
                ->title('Sitemap regenerated successfully!')
                ->body('sitemap.xml has been updated with '.app(SitemapGenerator::class)->urlCount().' URLs.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Failed to regenerate sitemap')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function defaultRobotsTxt(): string
    {
        return implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /cart',
            'Disallow: /checkout',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ]);
    }
}
