<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class HomepageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Homepage';
    protected static ?string $title = 'Homepage Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'settings/homepage';
    protected static string $view = 'filament.pages.settings.homepage';

    public array $data = [];

    public function mount(): void
    {
        $features   = Setting::get('homepage.features');
        $heroImages = json_decode(Setting::get('homepage.hero_images', '[]'), true) ?: [];

        $this->form->fill([
            'hero_title'               => Setting::get('homepage.hero_title', 'Your Receipts. Our Rolls.'),
            'hero_subtitle'            => Setting::get('homepage.hero_subtitle', 'Premium thermal receipt rolls for retail, F&B, and hospitality. Fast dispatch, bulk pricing, and delivery Australia-wide.'),
            'hero_badge'               => Setting::get('homepage.hero_badge', "Australia's No. 1 Thermal Roll Supplier"),
            'hero_cta_text'            => Setting::get('homepage.hero_cta_text', 'Shop Rolls'),
            'hero_cta_url'             => Setting::get('homepage.hero_cta_url', '/products'),
            'hero_secondary_text'      => Setting::get('homepage.hero_secondary_text', 'View Catalogue'),
            'hero_secondary_url'       => Setting::get('homepage.hero_secondary_url', '/products'),
            'hero_images'              => array_map(function ($url) {
                $endpoint = rtrim(config('filesystems.disks.s3.endpoint', ''), '/');
                $bucket   = config('filesystems.disks.s3.bucket', '');
                $prefix   = ($endpoint && $bucket) ? "{$endpoint}/{$bucket}/" : '';
                $path     = $prefix ? ltrim(str_replace($prefix, '', $url), '/') : ltrim(parse_url($url, PHP_URL_PATH) ?? $url, '/');

                return ['file' => $path];
            }, $heroImages),
            'hero_height'              => Setting::get('homepage.hero_height', '90'),
            'hero_overlay_opacity'     => Setting::get('homepage.hero_overlay_opacity', '40'),
            'hero_transition_style'    => Setting::get('homepage.hero_transition_style', 'fade'),
            'hero_transition_duration' => Setting::get('homepage.hero_transition_duration', '1500'),
            'hero_slide_interval'      => Setting::get('homepage.hero_slide_interval', '7'),
            // Products page banner
            'products_banner_image'    => Setting::get('products.banner_image') ? [Setting::get('products.banner_image')] : [],
            'products_banner_title'    => Setting::get('products.banner_title', 'All Products'),
            'products_banner_subtitle' => Setting::get('products.banner_subtitle', 'Thermal rolls & POS supplies — delivered Australia-wide.'),
            'show_features'            => (bool) Setting::get('homepage.show_features', true),
            'features'                 => $features ? json_decode($features, true) : [
                ['icon' => 'truck',   'title' => 'Free Shipping',  'text' => 'On orders over $200'],
                ['icon' => 'refresh', 'title' => 'Easy Returns',   'text' => '30-day return policy'],
                ['icon' => 'shield',  'title' => 'Secure Payment', 'text' => '100% protected'],
                ['icon' => 'support', 'title' => '24/7 Support',   'text' => 'Always here to help'],
            ],
            'show_featured_products'   => (bool) Setting::get('homepage.show_featured_products', true),
            'featured_title'           => Setting::get('homepage.featured_title', 'New Arrivals'),
            'featured_subtitle'        => Setting::get('homepage.featured_subtitle', 'Featured'),
            'show_cta_banner'          => (bool) Setting::get('homepage.show_cta_banner', true),
            'cta_title'                => Setting::get('homepage.cta_title', 'Ready to start shopping?'),
            'cta_subtitle'             => Setting::get('homepage.cta_subtitle', 'Join thousands of happy customers and enjoy premium products delivered fast.'),
            'show_category_pills'      => (bool) Setting::get('homepage.show_category_pills', true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Hero Content')
                    ->description('Text and buttons displayed over the homepage hero banner.')
                    ->schema([
                        TextInput::make('hero_badge')
                            ->label('Eyebrow Text')
                            ->placeholder("Australia's No. 1 Thermal Roll Supplier")
                            ->helperText('Small uppercase text shown above the headline. Leave empty to hide.')
                            ->columnSpanFull(),
                        TextInput::make('hero_title')
                            ->label('Headline')
                            ->required()
                            ->maxLength(200)
                            ->helperText('Keep it short — 3 to 6 words works best.'),
                        TextInput::make('hero_subtitle')
                            ->label('Subheading')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        TextInput::make('hero_cta_text')
                            ->label('Primary Button Text')
                            ->placeholder('Shop Rolls'),
                        TextInput::make('hero_cta_url')
                            ->label('Primary Button URL')
                            ->placeholder('/products'),
                        TextInput::make('hero_secondary_text')
                            ->label('Secondary Button Text')
                            ->placeholder('View Catalogue'),
                        TextInput::make('hero_secondary_url')
                            ->label('Secondary Button URL')
                            ->placeholder('/products'),
                    ])->columns(2),

                Section::make('Banner Images')
                    ->description('Upload full-bleed background images for the homepage slideshow. Images are stored on MinIO. Drag to reorder.')
                    ->schema([
                        Repeater::make('hero_images')
                            ->label('')
                            ->schema([
                                FileUpload::make('file')
                                    ->label('Image')
                                    ->image()
                                    ->disk('s3')
                                    ->directory('banners')
                                    ->visibility('public')
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('1920')
                                    ->imageResizeTargetHeight('1080')
                                    ->maxSize(5120)
                                    ->required()
                                    ->live()
                                    ->columnSpanFull()
                                    ->helperText('Recommended: 1920×1080px. Max 5 MB.'),
                                Placeholder::make('public_url')
                                    ->label('Public URL')
                                    ->content(function (Get $get): string {
                                        $path = $get('file');
                                        if (is_array($path)) {
                                            $path = array_values($path)[0] ?? null;
                                        }

                                        return $path ? Storage::disk('s3')->url($path) : '—';
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->reorderable()
                            ->collapsible()
                            ->maxItems(6)
                            ->defaultItems(0)
                            ->addActionLabel('Add banner image')
                            ->itemLabel(function (array $state): ?string {
                                $file = $state['file'] ?? null;
                                if (is_array($file)) {
                                    $file = array_values($file)[0] ?? null;
                                }

                                return $file ? basename((string) $file) : 'New image';
                            }),
                    ]),

                Section::make('Banner Display')
                    ->description('Controls hero height and overlay darkness on the homepage.')
                    ->schema([
                        TextInput::make('hero_height')
                            ->label('Hero Height')
                            ->numeric()->minValue(40)->maxValue(100)->suffix('vh')
                            ->helperText('Percentage of screen height. 90 = 90vh.'),
                        TextInput::make('hero_overlay_opacity')
                            ->label('Image Overlay Darkness')
                            ->numeric()->minValue(0)->maxValue(80)->suffix('%')
                            ->helperText('0 = no overlay, 50 = half dark.'),
                    ])->columns(2),

                Section::make('Slideshow Transition')
                    ->description('Animation between homepage slides. Only applies when multiple images are added.')
                    ->schema([
                        Select::make('hero_transition_style')
                            ->label('Transition Style')
                            ->options([
                                'fade'  => 'Fade — smooth crossfade',
                                'slide' => 'Slide — pan left to right',
                                'zoom'  => 'Zoom (Ken Burns) — slow zoom with crossfade',
                            ]),
                        TextInput::make('hero_slide_interval')
                            ->label('Slide Duration')
                            ->numeric()->minValue(3)->maxValue(30)->suffix('seconds')
                            ->helperText('Time each image stays visible. Default: 7'),
                        TextInput::make('hero_transition_duration')
                            ->label('Transition Speed')
                            ->numeric()->minValue(300)->maxValue(4000)->suffix('ms')
                            ->helperText('Animation duration. Default: 1500ms'),
                    ])->columns(3),

                Section::make('Products Page Banner')
                    ->description('Banner image shown at the top of the All Products page (/products). Upload an image and set the heading text.')
                    ->schema([
                        FileUpload::make('products_banner_image')
                            ->label('Banner Image')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->maxSize(4096)
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('600')
                            ->helperText('Recommended: 1920×600px landscape. Leave empty for a solid gradient background.')
                            ->columnSpanFull(),
                        TextInput::make('products_banner_title')
                            ->label('Page Title')
                            ->placeholder('All Products')
                            ->helperText('Shown as the large heading over the banner.'),
                        TextInput::make('products_banner_subtitle')
                            ->label('Subtitle')
                            ->placeholder('Thermal rolls & POS supplies — delivered Australia-wide.')
                            ->helperText('Short descriptive line shown below the title.'),
                    ])->columns(2),

                Section::make('Feature Blocks')
                    ->description('Small info blocks shown below the hero (shipping, returns, etc.).')
                    ->schema([
                        Toggle::make('show_features')
                            ->label('Show feature blocks'),
                        Repeater::make('features')
                            ->label('')
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Icon')
                                    ->placeholder('truck, refresh, shield, support')
                                    ->helperText('Choose: truck, refresh, shield, support, star, heart, clock, gift'),
                                TextInput::make('title')->required(),
                                TextInput::make('text')->label('Description'),
                            ])
                            ->columns(3)
                            ->defaultItems(4)
                            ->maxItems(6)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),

                Section::make('Featured Products')
                    ->description('The product grid section on the homepage.')
                    ->schema([
                        Toggle::make('show_featured_products')->label('Show featured products section'),
                        TextInput::make('featured_subtitle')->label('Label')->placeholder('Featured'),
                        TextInput::make('featured_title')->label('Section Title')->placeholder('New Arrivals'),
                    ]),

                Section::make('Product Listing Page')
                    ->description('Controls for the /products catalog page.')
                    ->schema([
                        Toggle::make('show_category_pills')
                            ->label('Show category filter pills')
                            ->helperText('Display clickable category buttons above the product grid.'),
                    ]),

                Section::make('CTA Banner')
                    ->description('Call-to-action banner near the bottom of the homepage.')
                    ->schema([
                        Toggle::make('show_cta_banner')->label('Show CTA banner'),
                        TextInput::make('cta_title')->label('Title')->placeholder('Ready to start shopping?'),
                        TextInput::make('cta_subtitle')->label('Subtitle'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $heroImages = array_values(array_filter(
            array_map(function ($item) {
                $path = $item['file'] ?? null;
                if (is_array($path)) {
                    $path = array_values($path)[0] ?? null;
                }
                if (! $path) {
                    return null;
                }

                return Storage::disk('s3')->url($path);
            }, $data['hero_images'] ?? [])
        ));

        $productsBannerImage = is_array($data['products_banner_image'] ?? null)
            ? (array_values($data['products_banner_image'])[0] ?? '')
            : ($data['products_banner_image'] ?? '');

        Setting::setMany([
            'homepage.hero_title'               => $data['hero_title'] ?? '',
            'homepage.hero_subtitle'            => $data['hero_subtitle'] ?? '',
            'homepage.hero_badge'               => $data['hero_badge'] ?? '',
            'homepage.hero_cta_text'            => $data['hero_cta_text'] ?? 'Shop Rolls',
            'homepage.hero_cta_url'             => $data['hero_cta_url'] ?? '/products',
            'homepage.hero_secondary_text'      => $data['hero_secondary_text'] ?? '',
            'homepage.hero_secondary_url'       => $data['hero_secondary_url'] ?? '/products',
            'homepage.hero_images'              => json_encode($heroImages),
            'homepage.hero_height'              => $data['hero_height'] ?? '90',
            'homepage.hero_overlay_opacity'     => $data['hero_overlay_opacity'] ?? '40',
            'homepage.hero_transition_style'    => $data['hero_transition_style'] ?? 'fade',
            'homepage.hero_transition_duration' => $data['hero_transition_duration'] ?? '1500',
            'homepage.hero_slide_interval'      => $data['hero_slide_interval'] ?? '7',
            'products.banner_image'             => $productsBannerImage,
            'products.banner_title'             => $data['products_banner_title'] ?? 'All Products',
            'products.banner_subtitle'          => $data['products_banner_subtitle'] ?? '',
            'homepage.show_features'            => $data['show_features'] ? '1' : '0',
            'homepage.features'                 => json_encode($data['features'] ?? []),
            'homepage.show_featured_products'   => $data['show_featured_products'] ? '1' : '0',
            'homepage.featured_title'           => $data['featured_title'] ?? 'New Arrivals',
            'homepage.featured_subtitle'        => $data['featured_subtitle'] ?? 'Featured',
            'homepage.show_cta_banner'          => $data['show_cta_banner'] ? '1' : '0',
            'homepage.cta_title'                => $data['cta_title'] ?? '',
            'homepage.cta_subtitle'             => $data['cta_subtitle'] ?? '',
            'homepage.show_category_pills'      => $data['show_category_pills'] ? '1' : '0',
        ]);

        Notification::make()->title('Homepage settings saved!')->success()->send();
    }
}
