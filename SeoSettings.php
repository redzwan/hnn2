<?php

namespace App\Filament\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SeoSettings extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'SEO Settings';

    protected static ?string $title = 'SEO Settings';

    protected static ?string $slug = 'seo-settings';

    protected static string $view = 'filament.pages.seo-settings';

    public ?string $siteTitle = '';

    public ?string $siteDescription = '';

    public ?string $siteKeywords = '';

    public ?string $ogImage = '';

    public ?string $ogTitle = '';

    public ?string $ogDescription = '';

    public function mount(): void
    {
        $this->siteTitle = config('seotools.meta.defaults.title');
        $this->siteDescription = config('seotools.meta.defaults.description');
        $this->siteKeywords = config('seotools.meta.defaults.keys');
        $this->ogTitle = config('seotools.opengraph.defaults.title');
        $this->ogDescription = config('seotools.opengraph.defaults.description');
        $this->ogImage = config('seotools.opengraph.defaults.images.0') ?? '';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Meta Tags')
                    ->description('Default SEO meta tags for your site')
                    ->schema([
                        TextInput::make('siteTitle')
                            ->label('Site Title')
                            ->placeholder('My Awesome Shop')
                            ->maxLength(70)
                            ->required(),
                        Textarea::make('siteDescription')
                            ->label('Site Description')
                            ->placeholder('Description for search engines...')
                            ->rows(3)
                            ->maxLength(160)
                            ->required(),
                        TextInput::make('siteKeywords')
                            ->label('Keywords')
                            ->placeholder('keyword1, keyword2, keyword3'),
                    ]),
                Section::make('Open Graph (Social Sharing)')
                    ->description('Settings for Facebook, Twitter, etc.')
                    ->schema([
                        TextInput::make('ogTitle')
                            ->label('OG Title')
                            ->placeholder('Title for social shares'),
                        Textarea::make('ogDescription')
                            ->label('OG Description')
                            ->rows(2)
                            ->placeholder('Description for social shares'),
                        FileInput::make('ogImage')
                            ->label('OG Image')
                            ->image()
                            ->placeholder('Upload an image for social sharing'),
                    ]),
            ])
            ->statePath('');
    }

    public function save(): void
    {
        $this->validate([
            'siteTitle' => ['required', 'string', 'max:70'],
            'siteDescription' => ['required', 'string', 'max:160'],
            'siteKeywords' => ['nullable', 'string'],
        ]);

        config([
            'seotools.meta.defaults.title' => $this->siteTitle,
            'seotools.meta.defaults.description' => $this->siteDescription,
            'seotools.meta.defaults.keys' => $this->siteKeywords,
            'seotools.opengraph.defaults.title' => $this->ogTitle ?: $this->siteTitle,
            'seotools.opengraph.defaults.description' => $this->ogDescription ?: $this->siteDescription,
        ]);

        if ($this->ogImage instanceof TemporaryUploadedFile) {
            $path = $this->ogImage->store('seo', 'public');
            config(['seotools.opengraph.defaults.images.0' => $path]);
        }

        Cache::forget('filament_seo_settings');

        $this->notify('SEO settings saved successfully!');
    }
}
+++++++
<?php

namespace App\Filament\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SeoSettings extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'SEO Settings';

    protected static ?string $title = 'SEO Settings';

    protected static ?string $slug = 'seo-settings';

    protected static string $view = 'filament.pages.seo-settings';

    public ?string $siteTitle = '';

    public ?string $siteDescription = '';

    public ?string $siteKeywords = '';

    public ?string $ogImage = '';

    public ?string $ogTitle = '';

    public ?string $ogDescription = '';

    public function mount(): void
    {
        $this->siteTitle = config('seotools.meta.defaults.title');
        $this->siteDescription = config('seotools.meta.defaults.description');
        $this->siteKeywords = config('seotools.meta.defaults.keys');
        $this->ogTitle = config('seotools.opengraph.defaults.title');
        $this->ogDescription = config('seotools.opengraph.defaults.description');
        $this->ogImage = config('seotools.opengraph.defaults.images.0') ?? '';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Meta Tags')
                    ->description('Default SEO meta tags for your site')
                    ->schema([
                        TextInput::make('siteTitle')
                            ->label('Site Title')
                            ->placeholder('My Awesome Shop')
                            ->maxLength(70)
                            ->required(),
                        Textarea::make('siteDescription')
                            ->label('Site Description')
                            ->placeholder('Description for search engines...')
                            ->rows(3)
                            ->maxLength(160)
                            ->required(),
                        TextInput::make('siteKeywords')
                            ->label('Keywords')
                            ->placeholder('keyword1, keyword2, keyword3'),
                    ]),
                Section::make('Open Graph (Social Sharing)')
                    ->description('Settings for Facebook, Twitter, etc.')
                    ->schema([
                        TextInput::make('ogTitle')
                            ->label('OG Title')
                            ->placeholder('Title for social shares'),
                        Textarea::make('ogDescription')
                            ->label('OG Description')
                            ->rows(2)
                            ->placeholder('Description for social shares'),
                        FileInput::make('ogImage')
                            ->label('OG Image')
                            ->image()
                            ->placeholder('Upload an image for social sharing'),
                    ]),
            ])
            ->statePath('');
    }

    public function save(): void
    {
        $this->validate([
            'siteTitle' => ['required', 'string', 'max:70'],
            'siteDescription' => ['required', 'string', 'max:160'],
            'siteKeywords' => ['nullable', 'string'],
        ]);

        config([
            'seotools.meta.defaults.title' => $this->siteTitle,
            'seotools.meta.defaults.description' => $this->siteDescription,
            'seotools.meta.defaults.keys' => $this->siteKeywords,
            'seotools.opengraph.defaults.title' => $this->ogTitle ?: $this->siteTitle,
            'seotools.opengraph.defaults.description' => $this->ogDescription ?: $this->siteDescription,
        ]);

        if ($this->ogImage instanceof TemporaryUploadedFile) {
            $path = $this->ogImage->store('seo', 'public');
            config(['seotools.opengraph.defaults.images.0' => $path]);
        }

        Cache::forget('filament_seo_settings');

        $this->notify('SEO settings saved successfully!');
    }
}
