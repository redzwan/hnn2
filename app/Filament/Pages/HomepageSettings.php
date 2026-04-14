<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
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
        $features = Setting::get('homepage.features');

        $this->form->fill([
            'hero_title' => Setting::get('homepage.hero_title', 'Shop Smarter, Live Better'),
            'hero_subtitle' => Setting::get('homepage.hero_subtitle', 'Discover premium products at unbeatable prices. Fast shipping, easy returns, and exceptional quality — every time.'),
            'hero_image' => Setting::get('homepage.hero_image'),
            'hero_badge' => Setting::get('homepage.hero_badge', 'New arrivals this week'),
            'hero_cta_text' => Setting::get('homepage.hero_cta_text', 'Shop Now'),
            'hero_cta_url' => Setting::get('homepage.hero_cta_url', '/products'),
            'hero_secondary_text' => Setting::get('homepage.hero_secondary_text', 'Join Free'),
            'hero_secondary_url' => Setting::get('homepage.hero_secondary_url', '/register'),
            'show_features' => (bool) Setting::get('homepage.show_features', true),
            'features' => $features ? json_decode($features, true) : [
                ['icon' => 'truck', 'title' => 'Free Shipping', 'text' => 'On orders over RM 200'],
                ['icon' => 'refresh', 'title' => 'Easy Returns', 'text' => '30-day return policy'],
                ['icon' => 'shield', 'title' => 'Secure Payment', 'text' => '100% protected'],
                ['icon' => 'support', 'title' => '24/7 Support', 'text' => 'Always here to help'],
            ],
            'show_featured_products' => (bool) Setting::get('homepage.show_featured_products', true),
            'featured_title' => Setting::get('homepage.featured_title', 'New Arrivals'),
            'featured_subtitle' => Setting::get('homepage.featured_subtitle', 'Featured'),
            'show_cta_banner' => (bool) Setting::get('homepage.show_cta_banner', true),
            'cta_title' => Setting::get('homepage.cta_title', 'Ready to start shopping?'),
            'cta_subtitle' => Setting::get('homepage.cta_subtitle', 'Join thousands of happy customers and enjoy premium products delivered fast.'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Hero Section')
                    ->description('The main banner at the top of the homepage.')
                    ->schema([
                        FileUpload::make('hero_image')
                            ->label('Hero Background Image')
                            ->image()
                            ->disk('public')
                            ->directory('homepage')
                            ->helperText('Recommended: 1920×800px or wider. Leave empty for a solid color background.'),
                        TextInput::make('hero_badge')
                            ->label('Badge Text')
                            ->placeholder('New arrivals this week')
                            ->helperText('Small text shown above the title. Leave empty to hide.'),
                        TextInput::make('hero_title')
                            ->label('Title')
                            ->required()
                            ->maxLength(200),
                        TextInput::make('hero_subtitle')
                            ->label('Subtitle')
                            ->maxLength(500),
                        TextInput::make('hero_cta_text')
                            ->label('Primary Button Text')
                            ->placeholder('Shop Now'),
                        TextInput::make('hero_cta_url')
                            ->label('Primary Button URL')
                            ->placeholder('/products'),
                        TextInput::make('hero_secondary_text')
                            ->label('Secondary Button Text')
                            ->placeholder('Join Free'),
                        TextInput::make('hero_secondary_url')
                            ->label('Secondary Button URL')
                            ->placeholder('/register'),
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
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('text')
                                    ->label('Description'),
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
                        Toggle::make('show_featured_products')
                            ->label('Show featured products section'),
                        TextInput::make('featured_subtitle')
                            ->label('Label')
                            ->placeholder('Featured'),
                        TextInput::make('featured_title')
                            ->label('Section Title')
                            ->placeholder('New Arrivals'),
                    ]),

                Section::make('CTA Banner')
                    ->description('Call-to-action banner near the bottom of the homepage.')
                    ->schema([
                        Toggle::make('show_cta_banner')
                            ->label('Show CTA banner'),
                        TextInput::make('cta_title')
                            ->label('Title')
                            ->placeholder('Ready to start shopping?'),
                        TextInput::make('cta_subtitle')
                            ->label('Subtitle'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $heroImage = ! empty($data['hero_image'])
            ? (is_array($data['hero_image']) ? array_key_first($data['hero_image']) : $data['hero_image'])
            : null;

        Setting::setMany([
            'homepage.hero_title' => $data['hero_title'] ?? '',
            'homepage.hero_subtitle' => $data['hero_subtitle'] ?? '',
            'homepage.hero_image' => $heroImage,
            'homepage.hero_badge' => $data['hero_badge'] ?? '',
            'homepage.hero_cta_text' => $data['hero_cta_text'] ?? 'Shop Now',
            'homepage.hero_cta_url' => $data['hero_cta_url'] ?? '/products',
            'homepage.hero_secondary_text' => $data['hero_secondary_text'] ?? '',
            'homepage.hero_secondary_url' => $data['hero_secondary_url'] ?? '/register',
            'homepage.show_features' => $data['show_features'] ? '1' : '0',
            'homepage.features' => json_encode($data['features'] ?? []),
            'homepage.show_featured_products' => $data['show_featured_products'] ? '1' : '0',
            'homepage.featured_title' => $data['featured_title'] ?? 'New Arrivals',
            'homepage.featured_subtitle' => $data['featured_subtitle'] ?? 'Featured',
            'homepage.show_cta_banner' => $data['show_cta_banner'] ? '1' : '0',
            'homepage.cta_title' => $data['cta_title'] ?? '',
            'homepage.cta_subtitle' => $data['cta_subtitle'] ?? '',
        ]);

        Notification::make()
            ->title('Homepage settings saved!')
            ->success()
            ->send();
    }
}
