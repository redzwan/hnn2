<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'site-settings';

    protected static string $view = 'filament.pages.site-settings';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'siteName' => Setting::get('site.name', config('app.name', 'MyShop')),
            'logo' => Setting::get('site.logo'),
            'favicon' => Setting::get('site.favicon'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Branding')
                    ->description('Customize your store identity')
                    ->schema([
                        TextInput::make('siteName')
                            ->label('Site Name')
                            ->placeholder('MyShop')
                            ->required()
                            ->maxLength(100),

                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->helperText('Recommended: PNG or SVG, max 200px height.')
                            ->imagePreviewHeight('60'),

                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->helperText('32×32 or 64×64 PNG. Or use the button below to generate one from your logo.')
                            ->imagePreviewHeight('40'),

                        FormActions::make([
                            FormAction::make('generateFavicon')
                                ->label('Generate Favicon from Logo')
                                ->icon('heroicon-o-sparkles')
                                ->color('gray')
                                // Arrow function ensures $this is always the Livewire component
                                ->action(fn () => $this->generateFaviconFromLogo()),
                        ]),
                    ]),
            ]);
    }

    public function generateFaviconFromLogo(): void
    {
        $logoPath = Setting::get('site.logo');

        if (! $logoPath) {
            Notification::make()
                ->title('No logo found. Please save a logo first.')
                ->warning()
                ->send();

            return;
        }

        $sourcePath = storage_path('app/public/'.$logoPath);

        if (! file_exists($sourcePath)) {
            Notification::make()
                ->title('Logo file not found on disk.')
                ->warning()
                ->send();

            return;
        }

        // Detect image type and load with GD
        $mime = mime_content_type($sourcePath);

        $src = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png' => imagecreatefrompng($sourcePath),
            'image/gif' => imagecreatefromgif($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default => null,
        };

        if (! $src) {
            Notification::make()
                ->title('Could not read logo image. Supported formats: JPG, PNG, GIF, WEBP.')
                ->danger()
                ->send();

            return;
        }

        // Create a 64×64 true-colour canvas with transparency
        $favicon = imagecreatetruecolor(64, 64);
        imagealphablending($favicon, false);
        imagesavealpha($favicon, true);
        $transparent = imagecolorallocatealpha($favicon, 0, 0, 0, 127);
        imagefill($favicon, 0, 0, $transparent);

        // Scale source to fit 64×64 preserving aspect ratio (letterbox)
        $srcW = imagesx($src);
        $srcH = imagesy($src);
        $scale = min(64 / $srcW, 64 / $srcH);
        $dstW = (int) round($srcW * $scale);
        $dstH = (int) round($srcH * $scale);
        $dstX = (int) round((64 - $dstW) / 2);
        $dstY = (int) round((64 - $dstH) / 2);

        imagecopyresampled($favicon, $src, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
        imagedestroy($src);

        // Use a unique filename to bust browser/Filepond caches
        $filename = 'site/favicon-'.time().'.png';
        $faviconStoragePath = storage_path('app/public/'.$filename);

        // Ensure the site directory exists
        if (! is_dir(dirname($faviconStoragePath))) {
            mkdir(dirname($faviconStoragePath), 0755, true);
        }

        if (! imagepng($favicon, $faviconStoragePath, 9)) {
            imagedestroy($favicon);
            Notification::make()
                ->title('Failed to write favicon file. Check storage permissions.')
                ->danger()
                ->send();

            return;
        }
        imagedestroy($favicon);

        // Delete any previously generated favicons so we don't accumulate files
        $oldFavicon = Setting::get('site.favicon');
        if ($oldFavicon && $oldFavicon !== $filename) {
            $oldPath = storage_path('app/public/'.$oldFavicon);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        Setting::set('site.favicon', $filename);

        Notification::make()
            ->title('Favicon generated successfully from your logo!')
            ->success()
            ->send();

        // Redirect so FileUpload (Filepond) fully re-initialises with the new favicon
        $this->redirect(static::getUrl());
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('site.name', $data['siteName']);

        $logoPath = ! empty($data['logo'])
            ? (is_array($data['logo']) ? array_key_first($data['logo']) : $data['logo'])
            : null;
        Setting::set('site.logo', $logoPath);

        $faviconPath = ! empty($data['favicon'])
            ? (is_array($data['favicon']) ? array_key_first($data['favicon']) : $data['favicon'])
            : null;
        Setting::set('site.favicon', $faviconPath);

        $this->form->fill([
            'siteName' => $data['siteName'],
            'logo' => Setting::get('site.logo'),
            'favicon' => Setting::get('site.favicon'),
        ]);

        Notification::make()
            ->title('Site settings saved successfully!')
            ->success()
            ->send();
    }
}
